<?php
namespace local\classes\cli;

use local\classes\mongodb as mongodb;

class MongoDbStatsMenu extends StatsMenu
{
    private $dir = __DIR__;

    private $stats;

    public function __construct(Flag $flags)
    {
        parent::__construct($flags);
        
        $this->stats = new mongodb\Stats();
    }

    protected function handleInput(string $text): void
    {
        $serverList = $this->stats->getServerList();
        if (empty($serverList) && $text != '4') {
            echo $this->text('You must add one or more servers, using option 4', 1) . "\n";
        } else {
            switch ($text) {
                case '1':
                    echo "\n\n" . str_repeat("_", 60) . "\n\n";
                    echo $this->printArray($this->stats->getVersion(), $this->text('Version', 1), 60);
                    echo str_repeat("_", 60) . "\n\n";
                    $this->continue();
                    echo str_repeat("_", 60) . "\n\n";
                    echo $this->printArray($serverList, $this->text('Server List', 1), 60);
                    echo str_repeat("_", 60) . "\n\n";
                    $this->continue();
                    echo str_repeat("_", 60) . "\n\n";
                    echo $this->printArray($this->stats->getStats(), $this->text('Stats', 1), 60);
                    echo str_repeat("_", 60) . "\n\n";
                    $this->continue();
                    break;
                case '2':
                   // uncomment this to test.
                   // $this->stats->addData();
                    
                    $this->getDBCollection();
                    
                    do {
                        echo $this->text("Keys to fetch: ", 1);
                    } while ($keys[] = readline());
                    
                    if (!empty($keys)) {
                        try {
                            echo $this->printArray($this->stats->getVariables($keys), $this->text('Variables', 1), 60);
                        } catch(\OutOfBoundsException | \UnexpectedValueException | \RuntimeException $oe) {
                            $this->text('Was not able get requested variables, invalid characters in variable names!', 5, 31, 47) . "\n";
                        }
                    }
                    $this->continue();
                    break;
                case '3':
                    $this->getDBCollection();
                    
                    if ($this->stats->flushCache()) {
                        echo $this->text('Cache Flushed', 1, 31, 47);
                    } else {
                        echo $this->text('Error Flushing the Cache', 1, 31, 47);
                    }
                    echo "\n";
                    $this->continue();
                    break;
                case '4':
                    echo $this->text(sprintf("Enter Server IP (default: %s): ", \common\Config::obj()->system['defaultIP'][$this->stats::DBTYPE]), 1);
                    $ip = readline();
                    
                    echo $this->text(sprintf("Enter Server Port (default: %s): ", \common\Config::obj()->system['defaultPort'][$this->stats::DBTYPE]), 1);
                    $port = readline();
                    try {
                        if ($this->stats->addServer($ip, $port) === true) {
                            $this->text('Added new server successfuly', 5, 31, 47) . "\n";
                        } else {
                            $this->text('Was not able to add new server!', 5, 31, 47) . "\n";
                        }
                    } catch(\UnexpectedValueException $e) {
                        echo $this->text($e->getMessage(), 5, 31, 47) . "\n";
                    }
                    break;
                case 'q':
                    exit(0);
                    break;
                default:
                    echo "\n\nCommand not found\n\n";
                    break;
            }
        }
    }
    
    public function getDBCollection() : void {
        $so = ServerOpt::obj();
        
        echo $this->text("Enter Database Name: ", 1);
        $db = readline();
        if ($this->stats->addDb($db)) {
            echo $this->text("Enter Collection Name: ", 1);
            $collection = readline();
            $this->stats->addCollection($collection);
        }
    }
}
