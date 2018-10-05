<?php

//define('LARAVEL_START', microtime(true));
/*
  |-------------------------------------------------------------------------
  | Custom Constants Needed for App Startup
  |-------------------------------------------------------------------------
  |
  | These custom constants are usually needed for App startup and to be used
  | in Config files only. For using constants in Controller/Model/View,
  | there is a separate constant file in config folder, which should
  | be used. However defining constants here will work throughout
  | the App as well.
  |
 */

require __DIR__ . '/constants.php';

/*
|--------------------------------------------------------------------------
| Register The Composer Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so we do not have to manually load any of
| our application's PHP classes. It just feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

require __DIR__ . '/../app/Helpers/Helpers.php';
