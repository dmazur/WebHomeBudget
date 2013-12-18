Simple home budget planning web app. In construction.

Licence: GPLv3

Instructions
============

You can find database schema in utils folder.

1. Insert your DB connection params to config.php (for now Postgres 9.1 only). You can find config.php template in config.template.php.
2. Set proper value to URLprefix variable in Panel/config.js.
3. Set proper value to RewriteBase in utils/.htaccess and copy it to Service directory
4. Set proper value to URLprefix in config.php
5. Copy two libs to Libs folder
* [Tonic](http://peej.github.com/tonic/). in Libs/Tonic folder
* [ExtJS4.1.1a](http://www.sencha.com/products/extjs/download/ext-js-4.1.1). in Libs/ExtJS4.1.1a folder

More recent versions of ExtJS should be handled soon.

Enjoy it
