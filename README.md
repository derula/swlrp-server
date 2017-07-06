# SWLRP server

## Setup instructions

- Clone the repository:
  ```
  git clone https://github.com/derula/swlrp-server.git
  ```
- Install dependencies:
  ```
  cd swlrp-server
  composer install
  ```
- Create the config file:
  ```
  cd swlrp-server/config
  cp config.yml.vendor config.yml
  ```
- Change the DB section of the config file according to needs
- (Optional): change the TinyMCE api key in the config file to your own (or just delete the section)
- Execute schema/setup.sql in your database
- Run the refresh_properties script:
  ```
  cd swlrp-server/scripts
  php refresh_properties.php
  ```

## Setting up the vhost

Simply use the ```public``` folder as webroot.
Also redirect all requests that would otherwise result in "file not found" back to ```public/index.php```,
e.g. using ModRewrite (Apache) or try_files (nginx).

## Changing available properties

In the Models/Profile section of config.yaml, you can set up which properties and texts will be available.
Properties are limited to 40 characters, texts allow up to 20000 characters and rich elements.
For each property or text, you can set certain options:

- name: The name under which the property or text is stored in the database
- title: The title of the field to be displayed to the user (only needed if different from the name)
- autocomplete: Use past values to offer the user an autocomplete popup (properties only)
- constraint: Limit the input field to certain types of data (properties only)

Note that after you changed the available property/text names (changed a name, added/removed a property/text),
you have to run the ```refresh_properties``` script again. This will:

- Mark the old properties as deleted
- Insert new properties

Note that no actual data will be deleted. If for some reason you want to bring back a previously-removed property,
simply add it back to the config and re-run the script. The old data will be available again.
