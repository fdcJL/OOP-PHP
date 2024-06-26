<?php

namespace App\Console;

use App\Console\Commands\CreateControllerCommand;
use App\Console\Commands\ServeCommand;
use App\Console\Commands\MakeTableCommand;
use App\Console\Commands\MigrationCommand;

class CommandRunner
{
    public function run($argv)
    {
        if (count($argv) < 2) {
            echo "Usage: php console <command>\n";
            exit(1);
        }

        $commandParts = explode(':', $argv[1]);
        switch ($commandParts[0]) {
            case 'serve':
                ServeCommand::run();
                break;
            case 'make':
                switch ($commandParts[1]) {
                    case 'controller':
                        CreateControllerCommand::run(array_slice($argv, 2));
                        break;
                    case 'table':
                        MakeTableCommand::run(array_slice($argv, 2));
                        break;
                    default:
                        echo "Unknown make command: {$argv[1]}\n";
                        break;
                }
                break;
            case 'migrate':
                if (isset($commandParts[1])) {
                    switch ($commandParts[1]) {
                        case 'rollback':
                            MigrationCommand::rollback();
                            break;
                        case 'fresh':
                            MigrationCommand::fresh();
                            break;
                        case 'truncate':
                            $table = null;
                            foreach ($argv as $arg) {
                                if (strpos($arg, '--table=') === 0) {
                                    $table = substr($arg, 8);
                                    break;
                                }
                            }
                            if ($table) {
                                MigrationCommand::truncate($table);
                            } else {
                                echo "Table name is required for truncate command".PHP_EOL;
                                echo "Usage: php console migrate:truncate --table=<tablename>";
                            }
                            break;
                        default:
                            echo "Unknown migration command: {$argv[1]}\n";
                            break;
                    }
                } else {
                    MigrationCommand::migrate();
                }
                break;
            default:
                echo "Command not found: {$argv[1]}\n";
                break;
        }
    }
}
