const express = require('express');
const app = express();
const fs = require('fs');
const opn = require('opn');
const loadIniFile = require('read-ini-file')
const Rcon = require('modern-rcon');
const dirToJson = require('dir-to-json');
const { v4: uuidv4 } = require('uuid');
const filesize = require("filesize");
const ms=require("./minestat.js")
const bodyParser = require('body-parser');

app.set('view engine', 'ejs');
app.set('views', __dirname+'/views')
app.use(express.static(__dirname + '/public'));
app.use((req, res, next) => {
	  res.set('Cache-Control', 'no-store')
	  next()
	})
app.use(bodyParser.urlencoded({ extended: true }));

config=JSON.parse(fs.readFileSync(__dirname+'/config.json','utf8'))

properties=loadIniFile.sync(config.server.path+"server.properties")

console.log(properties["rcon.password"])

const rcon = new Rcon(config.server.host,properties["rcon.password"]);

function runCommand(frase,callback){
	rcon.connect().then(() => {
	  return rcon.send(frase); // That's a command for Minecraft
	}).then(res => {
		callback(res)
	}).then(() => {
	  return rcon.disconnect();
	});
}

app.all('/', function(req, res) {
	var auth = req.headers['authorization'];
	if(!auth){
		res.setHeader('WWW-Authenticate', 'Basic realm="Secure Area"');
		res.statusCode = 401;
		res.end('<html><body>Need some creds son</body></html>');
	}else if(auth){
		var tmp = auth.split(' ');
        var buf = new Buffer(tmp[1], 'base64');
        var plain_auth = buf.toString();
        var creds = plain_auth.split(':');
        var username = creds[0];
        var password = creds[1];
        if((username == config.panel.login) && (password == config.panel.password)) {
            res.statusCode = 200;
            genDirTree(config.server.path,function (dirTree){
				res.render('index',{query:req.query,config,properties,dirTree});
			})
        }else {
            res.statusCode = 401;
            res.setHeader('WWW-Authenticate', 'Basic realm="Secure Area"');
            res.statusCode = 403;
            res.end('Forbidden');
        }
	}
});
function server_status(callback){
	ms.init(config.server.host, config.server.port, function(result)
	{
	  callback(ms);
	});
}

app.all('/api/status/',function (req,res){
	server_status(function (resp){
		res.json(resp)
	})
})
app.all('/rcon/server/',function (req,res){

	runCommand(req.query.cmd,function (resp){
		res.send(resp)
	})

})
app.all('/api/getlogs/',function (req,res){
	res.send(fs.readFileSync(config.server.path+'logs/latest.log','utf8'))
})
app.all('/api/get_live_info/',function (req,res){
	res.json({
		bans:JSON.parse(fs.readFileSync(config.server.path+'banned-players.json','utf8')),
		ops:JSON.parse(fs.readFileSync(config.server.path+'ops.json','utf8')),
		banip:JSON.parse(fs.readFileSync(config.server.path+'banned-ips.json','utf8')),
		wl:JSON.parse(fs.readFileSync(config.server.path+'whitelist.json','utf8'))
	})
})
app.all('/api/savefile/',function (req,res){
	var filePath=config.server.path+req.body.plugin
	console.log(filePath)
	fs.writeFile(filePath, req.body.code, (err) => {
	    // throws an error, you could also catch it here
	    if (err) throw err;

	    // success case, the file was saved
	    console.log('file saved!');
	});
	res.send("OK")
})
app.all('/api/new_/',function (req,res){
	if(req.body.type=="file"){
		var filePath=config.server.path+req.body.loc
		fs.appendFile(filePath, '', function (err) {
		  if (err) throw err;
		  console.log('Created new file!');
		});
	}else if(req.body.type=="folder"){
		fs.mkdir(config.server.path+req.body.loc, function(err) {
		  if (err) {
		    console.log(err)
		  } else {
		    console.log("New directory successfully created.")
		  }
		})
	}
	res.send("OK")
})
app.all('/api/delfiles/',function (req,res){
	Object.keys(req.body.files).forEach(function (p){
		if(req.body.files[p]){
			var filePath=config.server.path+req.body.path+p;
			if(p[p.length-1]=="/"){
				fs.rmdirSync(filePath, { recursive: true });
			}else{
				fs.unlink(filePath, function (err) {
				    if (err) throw err;
				    // if no error, file has been deleted successfully
				    console.log('File deleted!');
				});
			}

		}
	})
	res.send("OK")
})
app.all('/api/chname/',function (req,res){
	console.log(req.body)
	var from=config.server.path+req.body.path+req.body.from;
	var to=config.server.path+req.body.path+req.body.to;
	fs.rename(from, to, function(err) {
	    if ( err ) console.log('ERROR: ' + err);
	});
	console.log(from)
	res.send("OK")
})
function genDirTree(path,callback){
	var dirTree={}
	fs.readdir(path, (err, files) => {
		var len=0;
		files.forEach(file=> {
			var filePath=path+file
			var isFile=fs.statSync(filePath).isFile();
			if(isFile==false){
				len++
			}

		})
	  	files.forEach(file => {
	  		var filePath=path+file
	  		var stat=fs.statSync(filePath)
		  	var isFile=stat.isFile();
		    if(isFile){
		    	var data=fs.readFileSync(filePath,'utf8')
		    	dirTree[file]={isFile:true,perms:(stat.mode & 0o777).toString(8),size:filesize(stat.size),data,__uuid:uuidv4()}
		    }else{
		    	genDirTree(filePath+"/",function (dirTreex){
		    		dirTree[file]=dirTreex;
		    		dirTree[file].__uuid=uuidv4()
		    		dirTree[file].__perms=(stat.mode & 0o777)
		    		len--
		    		if(len==0){
		    			callback(dirTree)
		    		}
		    	})
		    }
	  	});
	  	if(len==0){
	  		callback(dirTree)
	  	}
	});
}

app.listen(config.panel.port,()=>{
	var url=`http://localhost:${config.panel.port}/`
	console.log(`Server is running on ${url}`);
	opn(url)
});
