<?php

namespace App\Console\Commands;

class ServeCommand {
    public static function run() {
        $host = 'localhost';
        $port = 8000;
        $docRoot = realpath(__DIR__ . '/../../../public');

        echo "PHP Development Server (http://$host:$port) started\n";
        echo "Press Ctrl-C to quit.\n";

        exec("php -S $host:$port -t $docRoot", $output);

        register_shutdown_function(function () use ($port) {
            $pid = self::findServerProcessId($port);
            if ($pid !== null) {
                exec("taskkill /F /PID $pid > NUL 2>&1");
                echo "PHP Development Server stopped\n";
            } else {
                echo "Failed to find PHP Development Server process\n";
            }
        });
    }

    private static function findServerProcessId($port) {
        exec("netstat -ano -p TCP", $output);

        foreach ($output as $line) {
            if (strpos($line, ':' . $port) !== false) {
                $parts = preg_split('/\s+/', $line);
                return end($parts);
            }
        }

        return null;
    }
}
