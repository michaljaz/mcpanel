# mcpanel
Prosty panel zarządzania serwerem minecraftowym.<br>


<h3>Instalacja:</h3>

```bash
git clone https://github.com/michaljaz/mcpanel
cd mcpanel
npm i
npm start
```

> **Ważne:** Przy uruchamianiu potrzebna jest najnowsza wersja node.js i npm.<br>
>  Przed uruchomieniem serwera zapoznaj się z plikiem config.json</u>.<br>
>  Ważne jest to aby ścieżka do twojego servera kończyła się na <b>'/'</b> (np.: '/home/user/Desktop/server<b>/</b>')</u>.<br>

<h3>Plik konfiguracyjny(config.json):</h3>

```php
{
	"server":{
		"host":"localhost",
		"port":25565,
		"path":"/ścieżka/do/plików/twojego/serwera/"
	},
	"panel":{
		"login":"admin",
		"password":"password",
		"port":8080
	}
}
```
<img src="https://i.ibb.co/FBHLXFZ/screen1.png"
     alt="Screenshot"/>
     <br><br>
<img src="https://i.ibb.co/wQzH6dq/screen2.png"
     alt="Screenshot"/>
     <br><br>
<img src="https://i.ibb.co/h9371tD/screen3.png"
     alt="Screenshot"/>
     <br><br>
