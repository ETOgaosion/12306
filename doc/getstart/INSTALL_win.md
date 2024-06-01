# Windows Installation

[TOC]

## Prerequisites

- [Git](https://git-scm.com/download/win)
- [GitHub](https://github.com), you should create your account, **add your device's ssh public key to your account**, inform @ETOgaosion your GitHub username.

## Installation

On Windows, we should use XAPP stack (Apache, PHP, Postgresql) to run our project, which is Web Server, Backend Language and Database. But unfortunately, this toolchain has been deprecated, and we need specific version of each tool, thus we need to install them separately.

### Video guide

You can follow this video to install the tools, combining with the guide below.

[https://www.youtube.com/watch?v=461kxsPM_nU](https://www.youtube.com/watch?v=461kxsPM_nU)

### Postgresql

#### Download Postgresql

Use this [download link](https://get.enterprisedb.com/postgresql/postgresql-14.11-1-windows-x64-binaries.zip), and extract it, move the `pgsql` folder to `d:\pgsql`(or any other path you like, we call the absolute path `$PGSQL_ROOT`, if we mention it later, replace it with your path)

Create a directory named `data` in `$PGSQL_ROOT`

#### Install Postgresql

- Open a cmd with **admin authority**
- Navigate to `$PGSQL_ROOT\bin`
- Run `initdb -D $PGSQL_ROOT\data -U postgres` (`initdb -D d:\pgsql\data -U postgres`)
- Install Postgresql Service by running `pg_ctl register -N "PostgreSQL 14" -D $PGSQL_ROOT\data` (`pg_ctl register -N "PostgreSQL 14" -D d:\pgsql\data`),
- Check if the service is installed by `win + R`, `services.msc` (or simply input `services` in search bar of your pc), find `PostgreSQL 14`, then right click and start it
- Check CLI tool: run `psql -U postgres`, if you see `postgres=#`, then you have installed Postgresql successfully, input `\q` to exit

#### Configure Postgresql Client

- Input `%APPDATA%` in your file explorer (`C:\Users\[$USER]\AppData\Roaming\`), create or navigate to `postgresql` folder, create a file named `psqlrc.conf`, input this line:

```conf
SET client_encoding = 'UTF8';
```

#### Configure PgAdmin

- Navigate to `$PGSQL_ROOT\pgAdmin 4\runtime` (`D:\pgsql\pgAdmin 4\runtime`), run `pgAdmin4.exe`
- After the GUI app open, click `Add New Server`
  - In General, `Name` input `localhost` 
  - In Connection, `Host name/address` input `localhost`, `Port` input `5432`, `postgres` in `Maintain database` and `Username`, empty in `Password` (currently `postgres` user has no password), check `Save password` option, then click `Save`
- Then the server should be connected, you can see the `localhost` server in the left panel
- Navigate to `localhost` $\rightarrow$ `Login/Group Roles` $\rightarrow$ `postgres` $\rightarrow$ `Properties`, click on the left top `edit` button (like a pen)
- In `Definition` tab, input a password in `Password`: `123456` (our php program also use this password, please do not change it), then click on save button

[//]: # (##### [optional] Configure Postgresql to force use password)

[//]: # ()
[//]: # (- Open `$PGSQL_ROOT\data\pg_hba.conf` &#40;`D:\psql\data\pg_hba.conf`&#41;, change this line at the end of the file:)

[//]: # ()
[//]: # (```conf)

[//]: # (# "local" is for Unix domain socket connections only)

[//]: # (local   all             all                                     trust)

[//]: # (# IPv4 local connections:)

[//]: # (host    all             all             127.0.0.1/32            trust)

[//]: # (# IPv6 local connections:)

[//]: # (host    all             all             ::1/128                 trust)

[//]: # (```)

[//]: # ()
[//]: # (to:)

[//]: # ()
[//]: # (```conf)

[//]: # (# "local" is for Unix domain socket connections only)

[//]: # (local   all             all                                     password)

[//]: # (# IPv4 local connections:)

[//]: # (host    all             all             127.0.0.1/32            password)

[//]: # (# IPv6 local connections:)

[//]: # (host    all             all             ::1/128                 password)

[//]: # (```)

[//]: # ()
[//]: # (This action is mainly for program execution correctly, for convenience, add a `.pgpass` file in your user directory &#40;`C:\Users\yourname`&#41;, input this line:)

[//]: # ()
[//]: # (```)

[//]: # (localhost:5432:postgres:postgres:123456)

[//]: # (```)

Now close the pgAdmin 4 and reopen it, you should input the password `123456` and check `save password` box.

### PHP

#### Download PHP

[Download Link](https://windows.php.net/downloads/releases/latest/php-8.3-Win32-vs16-x64-latest.zip), we use the thread safe one, extract it to `d:\php` (or any other path you like, we call the absolute path `$PHP_ROOT`, if we mention it later, replace it with your path)

Copy the `php.ini-development` file to `php.ini` in `$PHP_ROOT` folder

#### Configure PHP

- Navigate to `;extension_dir = "ext"`, change it to:

```conf
extension_dir = "$PHP_ROOT\ext"
```

in our case:

```conf
extension_dir = "d:\php\ext"
```

- Navigate to `;extension=pdo_pgsql`, remove the `;` before it
- Navigate to `;extension=pgsql`, remove the `;` before it

### Apache

#### Download Server and Project

- First, download [this redistributable file](https://aka.ms/vs/17/release/VC_redist.x64.exe)
- Then download [Apache 2.4.59](https://www.apachelounge.com/download/VS17/binaries/httpd-2.4.59-240404-win64-VS17.zip)
- Extract the zip file, copy the `Apache24` folder to `d:\apache` (or any other path you like, we call the absolute path `$SERVER_ROOT`, if we mention it later, replace it with your path)
- Then you can clone this project to `$SERVER_ROOT\htdocs\12306` (we call this path `$PROJECT_ROOT`), in git bash, run `git clone git@github.com:ETOgaosion/12306.git` (make sure you have added your ssh public key to your GitHub account, and informed @ETOgaosion your GitHub username)
- Download the full dataset into project `$SERVER_ROOT\htdocs\12306\app\database`, replace the original `data_process` folder.

#### Install Server and Configuration

##### Apache Configuration

**Notice: whenever you change the configuration, to make it take effect, you must restart the service**

Configuration file `$SERVER_ROOT\conf\httpd.conf` (`d:\apache\conf\httpd.conf`)

- Change `ServerRoot` to `$SERVER_ROOT` (`d:\apache`)
- Change `ServerName` to `localhost`, remember to uncomment it by remove the `#` before the line
- Navigate to the end of the file, add these lines:

```apacheconf
LoadModule php_module "$PHP_ROOT\php8apache2_4.dll"
AddHandler application/x-httpd-php .php
PHPIniDir "$PHP_ROOT"

LoadFile "$PHP_ROOT\libpq.dll"
```

在我们的case:

```apacheconf
LoadModule php_module "d:\php\php8apache2_4.dll"
AddHandler application/x-httpd-php .php
PHPIniDir "d:\php"

LoadFile "d:\php\libpq.dll"
```

- Navigate to:

```apacheconf
<IfModule dir_module>
    DirectoryIndex index.html
</IfModule>
```

Change it to:

```apacheconf
<IfModule dir_module>
    DirectoryIndex index.html index.php
</IfModule>
```

##### Apache Installation

You can read the original guidance in the `httpd-2.4.59-240404-win64-VS17.zip` zip file `ReadMe.txt`, that is:

- Open a cmd with **admin authority**
- Navigate to `$SERVER_ROOT\bin`
- Run `httpd -k install`
- Check if the service is installed by `win + R`, `services.msc` (or simply input `services` in search bar of your pc), find `Apache2.4`, then right click and start it
- Input `localhost` in your browser, you should see the Apache default page `It works!`
- Check php installation: create a file `info.php` in `$SERVER_ROOT\htdocs`, input `<?php phpinfo(); ?>`, then input `localhost/info.php` in your browser, you should see the php information page
- Check postgresql connection: edit the `info.php` file to:

```php
<?php
$conn = pg_connect("host=localhost port=5432 dbname=postgres user=postgres password=123456");
if (!$conn) {
    echo "An error occurred.\n";
    exit;
}
echo "Connected successfully.\n";
```

And access `localhost/info.php` in your browser, you should see `Connected successfully.`

### IDE

#### Install PhpStorm

Use your UCAS account to register Jetbrains and get a free student license. Download phpstorm and use it to open this project (`$PROJECT_ROOT`, `D:\apache\htdocs\12306`).

#### Configure Database

On right bar there is a `Database` tab, click it and press `+` button to add a new data source, choose `PostgreSQL`, fill in the information as [before, when you connect to pgAdmin](#configure-pgadmin)

#### Preprocess Data

If you have the latest `postprocess_data` directory, use it directly, otherwise, you need to preprocess the data by running the python script in `app/database` folder.

```bash
python pre_process.py
python main.py
```

You should refresh data every 10 days by:

```bash
python refresh_data.py
```

#### Input data into database

Now navigate to `app/database/data_process`. Check whether the paths in `data_load[_win/_mac].sql` match your project path, if not, modify them to the real `postprocess_data`. If windows users use the steps above to configure projects, you can run:

```bash
psql -f data_load_win.sql -U postgres -d postgres
```

Then navigate to `app/database/sql`. If you use windows, Run these sql cmds one by one in cmd or pgAdmin:

```bash
psql -f enums.sql -U postgres -d postgres

psql -f create_tables.sql -U postgres -d postgres

psql -f lib_funcs.sql -U postgres -d postgres

psql -f table_funcs.sql -U postgres -d postgres

psql -f requirement_funcs.sql -U postgres -d postgres

psql -f data_load_mac.sql -U postgres -d postgres
```

#### Test Data Input

Open pgAdmin and refresh, navigate to `Server` $\rightarrow$ `Databases` $\rightarrow$ `postgres` $\rightarrow$ `Schemas` $\rightarrow$ `public` $\rightarrow$ `Tables`, you should see all the tables we have created. 

Click any table twice will show you the data in it, almost any table related with train and city should have data. If there is no data, please check the sql outputs and try to run them again.

#### Install Node Modules

```bash
npm install ol
```