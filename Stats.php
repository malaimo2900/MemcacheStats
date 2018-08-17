<?php
$common_php_dir = '../php_common';
$common_autoload_file = $common_php_dir . '/autoload.php';
require ($common_autoload_file);

$cli_php_dir = '../php_cli';
$cli_autoload_file = $cli_php_dir . '/autoload.php';
require ($cli_autoload_file);

require ('./local/autoload.php');

use cli\classes as cli;
use local\classes\cli as localCli;

\common\Config::obj(__DIR__ . '/config/config.ini');

$rm = new localCli\MemcacheStatsMenu($argv, $argc);
$rm->readLine();