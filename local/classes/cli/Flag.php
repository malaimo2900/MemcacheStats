<?php 
namespace local\classes\cli;

use cli\classes as cli;

class Flag extends cli\Flag {
    protected $help;
    protected $dir;
    
    protected $config = array(
        'help' => array(FILTER_VALIDATE_BOOLEAN),
        'dir' => array(FILTER_SANITIZE_STRING)
    );
}