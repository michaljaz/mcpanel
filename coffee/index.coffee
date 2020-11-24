express = require "express"
app = express()
fs = require "fs"
opn = require "opn"
loadIniFile = require "read-ini-file"
Rcon = require "modern-rcon"
dirToJson = require "dir-to-json"
uuidv4 = require('uuid').v4
filesize = require "filesize"
ms=require "./minestat.js"
bodyParser = require "body-parser"

app.set 'view engine', 'ejs'
app.set 'views', "#{__dirname}/views"
app.use express.static("#{__dirname}/public")
app.use (req, res, next)->
	res.set 'Cache-Control', 'no-store'
	next()
	return
app.use bodyParser.urlencoded({ extended: true })

config=JSON.parse fs.readFileSync("#{__dirname}/config.json",'utf8')

properties=loadIniFile.sync "#{config.server.path}server.properties"

console.log properties["rcon.password"]

rcon = new Rcon config.server.host,properties["rcon.password"]

genDirTree=(path,callback)->
	dirTree={}
	fs.readdir path,(err, files)->
		len=0
		files.forEach (file)->
			filePath=path+file
			isFile=fs.statSync(filePath).isFile()
			if isFile is false
				len++
			return
		files.forEach (file)->
			filePath=path+file
			stat=fs.statSync filePath
			isFile=stat.isFile()
			if isFile
				data=fs.readFileSync filePath,'utf8'
				dirTree[file]={
					isFile:true
					perms:(stat.mode & 0o777).toString(8)
					size:filesize(stat.size)
					data
					__uuid:uuidv4()
				}
			else
				genDirTree "#{filePath}/",(dirTreex)->
					dirTree[file]=dirTreex
					dirTree[file].__uuid=uuidv4()
					dirTree[file].__perms=(stat.mode & 0o777)
					len--
					if len is 0
						callback dirTree
					return
			return
		if len is 0
			callback dirTree
		return
	return

runCommand=(frase,callback)->
	rcon.connect().then(()->
		return rcon.send frase
	).then((res)->
		callback res
	).then(()->
		return rcon.disconnect()
	)
	return

server_status=(callback)->
	ms.init config.server.host, config.server.port,(result)->
		callback ms
		return
	return

app.all "/",(req, res)->
	auth = req.headers['authorization']
	if not auth
		res.setHeader "WWW-Authenticate", "Basic realm='Secure Area'"
		res.statusCode = 401
		res.end "<html><body>Need some creds son</body></html>"
	else if auth
		tmp = auth.split " "
		buf = new Buffer tmp[1], "base64"
		plain_auth = buf.toString()
		creds = plain_auth.split ":"
		username = creds[0]
		password = creds[1]
		if (username is config.panel.login) and (password is config.panel.password)
			res.statusCode = 200
			genDirTree config.server.path,(dirTree)->
				res.render 'index',{query:req.query,config,properties,dirTree}
				return
		else
			res.statusCode = 401
			res.setHeader "WWW-Authenticate","Basic realm='Secure Area'"
			res.statusCode = 403
			res.end "Forbidden"
	return

app.all "/api/status/",(req,res)->
	server_status (resp)->
		res.json resp
		return
	return

app.all "/rcon/server/",(req,res)->
	runCommand req.query.cmd,(resp)->
		res.send resp
		return
	return

app.all "/api/getlogs/",(req,res)->
	res.send fs.readFileSync("#{config.server.path}logs/latest.log","utf8")
	return

app.all "/api/get_live_info/",(req,res)->
	res.json {
		bans:JSON.parse fs.readFileSync("#{config.server.path}banned-players.json","utf8")
		ops:JSON.parse fs.readFileSync("#{config.server.path}ops.json","utf8")
		banip:JSON.parse fs.readFileSync("#{config.server.path}banned-ips.json","utf8")
		wl:JSON.parse fs.readFileSync("#{config.server.path}whitelist.json","utf8")
	}
	return

app.all "/api/savefile/",(req,res)->
	filePath="#{config.server.path}#{req.body.plugin}"
	console.log filePath
	fs.writeFile filePath, req.body.code,(err)->
		if err
			throw err
		console.log "file saved!"
		return
	res.send "OK"
	return

app.all "/api/new_/",(req,res)->
	if req.body.type is "file"
		filePath="#{config.server.path}#{req.body.loc}"
		fs.appendFile filePath, '',(err)->
			if err
				throw err
			console.log 'Created new file!'
			return
		return
	else if req.body.type is "folder"
		fs.mkdir "#{config.server.path}#{req.body.loc}",(err)->
			if err
				console.log err
			else
				console.log "New directory successfully created."
			return
	res.send "OK"
	return

app.all "/api/delfiles/",(req,res)->
	for p of req.body.files
		if req.body.files[p]
			filePath="#{config.server.path}#{req.body.path}#{p}"
			if p[p.length-1] is "/"
				fs.rmdirSync filePath, { recursive: true }
			else
				fs.unlink filePath,(err)->
					if err
						throw err
					console.log "File deleted!"
					return
	res.send "OK"
	return

app.all "/api/chname/",(req,res)->
	console.log req.body
	fr="#{config.server.path}#{req.body.path}#{req.body.from}"
	to="#{config.server.path}#{req.body.path}#{req.body.to}"
	fs.rename fr, to,(err)->
		if err
			console.log "ERROR: #{err}"
		return
	console.log fr
	res.send "OK"
	return

app.listen config.panel.port,()->
	url="http://localhost:#{config.panel.port}/"
	console.log "Server is running on #{url}"
	opn url
	return
