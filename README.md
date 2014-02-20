Simple home budget planning web app. In construction.

Licence: GPLv3

Instructions
============

You can find database schema in utils folder.

1. Rename Panel/template.config.js to Panel/config.js and fill it
2. Set proper value to RewriteBase in utils/.htaccess and copy it to Service directory
3. Rename config.template.php to config.php and fill it
4. Copy two libs to Libs folder
* [Tonic](http://peej.github.com/tonic/). in Libs/Tonic folder
* [ExtJS4.1.1a](http://www.sencha.com/products/extjs/download/ext-js-4.1.1). in Libs/ExtJS4.1.1a folder

More recent versions of ExtJS should be handled soon.

Apache configuration and mods enable
============

Requirements:
Apache
PostgreSQL 9.1 with PDO i PHP.

Additional install:
```shell
sudo apt-get install php5-json php5-pgsql
```

```shell
a2enmod userdir
a2enmod php5
a2enmod rewrite
```

mods-enabled/php5.conf
```
<IfModule mod_php5.c>
    <FilesMatch "\.ph(p3?|tml)$">
    SetHandler application/x-httpd-php
    </FilesMatch>
    <FilesMatch "\.phps$">
    SetHandler application/x-httpd-php-source
    </FilesMatch>
    # To re-enable php in user directories comment the following lines
    # (from <IfModule ...> to </IfModule>.) Do NOT set it to On as it
    # prevents .htaccess files from disabling it.
    # <IfModule mod_userdir.c>
    #     <Directory /home/*/public_html>
    #         php_admin_value engine Off
    #     </Directory>
    # </IfModule>
</IfModule>
```

cat mods-enabled/userdir.conf
```
<IfModule mod_userdir.c>
        UserDir public_html
        UserDir disabled root

        <Directory /home/*/public_html>
                #AllowOverride FileInfo AuthConfig Limit Indexes
                #Options MultiViews Indexes SymLinksIfOwnerMatch IncludesNoExec
                AllowOverride All
        Options Indexes FollowSymLinks
        <Limit GET POST OPTIONS PUT DELETE>
                        Order allow,deny
                        Allow from all
                </Limit>
                <LimitExcept GET POST OPTIONS PUT DELETE>
                        Order deny,allow
                        Deny from all
                </LimitExcept>
        </Directory>
</IfModule>
```
^Take a look that it add PUT and DELETE methods

Cron and Cyclic
============
In order to create Bills from cyclic model we "for now" perform script
it is in utils/cylic_generaion.php, before you set up cron, you must change paths from require_once to absolute paths!

```
crontab -e
00 11 * * * php /home/_your_home_/public_html/utils/cyclic_generation.php
```


## Contributing (always welcome :) )

1. Fork it
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Added some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request


Enjoy it
