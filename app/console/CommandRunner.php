<?php

namespace App\Console;

use App\Console\Commands\CreateControllerCommand;
use App\Console\Commands\ServeCommand;
use App\Console\Commands\MakeMigrationCommand;
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
                    case 'migration':
                        MakeMigrationCommand::run(array_slice($argv, 2));
                        break;
                    default:
                        echo "Unknown make command: {$argv[1]}\n";
                        break;
                }
                break;
            case 'migrate':
                MigrationCommand::migrate();
                break;
            case 'rollback':
                MigrationCommand::rollback();
                break;
            default:
                echo "Command not found: {$argv[1]}\n";
                break;
        }
    }
}
