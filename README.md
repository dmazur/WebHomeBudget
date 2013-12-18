==============================

Simple home budget planning web app. In construction.


Instructions
============

You can find database schema in utils folder.

1. Insert your DB connection params to config.php (for now Postgres 9.1 only). You can find config.php template in config.template.php.
2. Set proper value to URLprefix variable in Panel/app.js.
3. Set proper value to RewriteBase in Service/.htaccess
4. Set proper value to Location in Panel/index.php
5. Copy two libs to Libs folder
5.1 [Tonic](http://peej.github.com/tonic/). in Libs/Tonic folder
5.2 [ExtJS4.1.1a](http://www.sencha.com/products/extjs/download/ext-js-4.1.1). in Libs/ExtJS4.1.1a folder

More recent versions of ExtJS should be handled soon.

Enjoy it
