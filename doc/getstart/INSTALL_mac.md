# MacOS Installation

## XAPP Installation

Do not use system default apache. Remove it from system service:

```bash
sudo launchctl unload -w /System/Library/LaunchDaemons/org.apache.httpd.plist
```

Use homebrew version of httpd, php:

```bash
brew install httpd
brew install php@8.3
brew services start php@8.3
brew services start httpd
```

Install pgsql from [https://postgres.app/](https://postgres.app/), and add it to your path:

```bash
sudo mkdir -p /etc/paths.d &&
echo /Applications/Postgres.app/Contents/Versions/latest/bin | sudo tee /etc/paths.d/postgresapp
```

## Apache Configuration

Same as Windows, but the path is different:

## Postgresql Configuration

Change `pg_hba.conf` only allow password login.

## IDE

Same as Windows.

### Input data to database

For mac's` data_load.sql`, please run `data_load.sh` in `data_process` folder to generate new one.

The files and orders are same as windows.