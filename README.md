# mcpanel
Panel Zarządzania serwerem minecraftowym<br>
<h1>Instalacja:</h1>

```bash
git clone https://github.com/michaljaz/mcpanel

#Pamiętaj aby ustawić prawidłowe uprawnienia do plików serwera
#Przykład:
sudo chmod 777 -R /ścieżka/do/plików/serwera
sudo chown www-data:www-data -R /ścieżka/do/plików/serwera


```
<h1>Plik konfiguracyjny:</h1>
```php
#adres serwera (ip)
$serverHost="localhost";

#RCON
$rconPort=25575;
$rconPassword="pAsSwOrD";

#Ścieżka od serwera
$serverPath="path/to/minecraft/server/folder";

#Panel administracyjny RCON
$panel_login='admin';
$panel_password='password';
?>
```