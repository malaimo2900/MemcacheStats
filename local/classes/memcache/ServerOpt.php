<?php
namespace local\classes\memcache;

use \local\classes as local;

class ServerOpt extends local\ServerOpt {
    protected $key;
    
    protected $config = array(
        'key' => array(FILTER_SANITIZE_STRING)
    );
}