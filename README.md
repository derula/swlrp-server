# SWLRP Roleplay Profile Add-On - Server

## Dependencies

- For Ubuntu with Apache:
  ```
  apt-get install composer apache2 libapache2-mod-php php7.0-mysql mysql-server-5.7
  ```
## Setup instructions (command-line)

- Clone the repository:
  ```
  git clone https://github.com/derula/swlrp-server.git
  ```
- Install dependencies:
  ```
  cd swlrp-server/
  composer install
  ```
- Create the database in the MySQL (if it doesn't already exist):
  ```
  mysql -u <username> -p
  CREATE DATABASE <dbname>
  exit
  ```
- Create the config file in swlrp-server/config/
  ```
  cd config/
  cp config.yml.vendor config.yml
  ```
- Change the DB section of the config file according to needs
- Execute schema/setup.sql in your database
  ```
  cd schema/
  mysql <dbname> -u <username> -p < setup.sql
  ```
- Run the refresh_properties script in swlrp-server/scripts/
  ```
  cd swlrp-server/scripts
  php refresh_properties.php
  ```

## Setting up the vhost

Simply use the ```swlrp-server/public``` folder as webroot.
Also redirect all requests that would otherwise result in a "404 file not found" back to ```swlrp-server/public/index.php```,
e.g. using ModRewrite (Apache) or try_files (nginx).

- In Apache, add the following to the .conf file:
  ```
  RewriteEngine On
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteRule ^(.*)? /index.php?uri=/$1 [L,NC,QSA]
  ```
  Also make sure to enable the rewrite mod and restart Apache:
  ```
  a2enmod rewrite
  service apache2 restart
  ```

## Changing available properties

In the Models/Profile section of config.yaml, you can set up which properties and texts will be available.
Properties are limited to 40 characters, texts allow up to 20000 characters and rich elements.
For each property or text, you can set certain options:

- name: The name under which the property or text is stored in the database (equals key if not specified)
- title: The title of the field to be displayed to the user (only needed if different from the name)
- autocomplete: Use past values to offer the user an autocomplete popup (properties only)
- constraint: Limit the input field to certain types of data (properties only)

Note that after you changed the available property/text names (changed a name, added/removed a property/text),
you have to run the ```refresh_properties``` script again. This will:

- Mark the old properties as deleted
- Insert new properties

Note that no actual data will be deleted. If for some reason you want to bring back a previously-removed property,
simply add it back to the config and re-run the script. The old data will be available again.

## Live operation notes

In order for the page to work inside the in-game browser, you need to be aware that the game is using a really old version of Chrome.
This means:

- You need to use conservative / compatible HTTPS settings (even if it's not the most secure... better than no HTTPS at all?)
- The JavaScript needs to be transpiled into something the game understands.
  - Previously, [talyssonoc/php-babel-transpiler](https://github.com/talyssonoc/php-babel-transpiler) was used for this purpose.
    However, this package seems abandoned, and was removed from ```composer.json```. Transpiled assets are now available in
    ```public/assets/*.compat.js```. If you need to change the JavaScript, note that you will need to transpile and commit these
    files manually.

## Legal note

For the homepage, the repository contains some loading screens that are Copyright Material of Funcom Oslo AS. We have
written permission from Funcom's legal department to use these on
[the project's current "official" installation](http://profile.swlrp.com). However, if you want to launch a fork
website, you may need to ask for permission separately.

The emoticons are "Copyright (C) 2001-Infinity, Oscar Gruno &amp; Andy Fedosjeenko," and free to use if credited.
(See also /public/assets/images/emoticons/credits.txt)
