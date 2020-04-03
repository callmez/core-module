<?php

namespace Modules\Core\Module\Traits;

use Illuminate\Support\Str;
use Illuminate\Console\Events\CommandFinished;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Request;
use Symfony\Component\Console\Output\ConsoleOutput;

trait HasSeeds
{
    /**
     * !!!需注意Seeds的里的文件必须在composer有索引的php文件. 否则会出现找不到文件的报错!!!
     *
     * @param $path
     */
    protected function loadSeedsFrom($path)
    {
        if (
            $this->app->runningInConsole() &&
            $this->isConsoleCommandContains([ 'db:seed', '--seed' ], [ '--class', 'help', '-h' ])
        ) {
            Event::listen(CommandFinished::class, function (CommandFinished $event) use ($path) {
                // Accept command in console only,
                // exclude all commands from Artisan::call() method.
                if ($event->output instanceof ConsoleOutput) {
                    $this->addSeedsFrom($path);
                }
            });
        }
    }

    /**
     * Get a value that indicates whether the current command in console
     * contains a string in the specified $fields.
     *
     * @param string|array $contain_options
     * @param string|array $exclude_options
     *
     * @return bool
     */
    protected function isConsoleCommandContains($contains, $excludes = null) : bool
    {
        $args = Request::server('argv', null);
        if (is_array($args)) {
            $command = implode(' ', $args);
            if (Str::contains($command, $contains) && ($excludes == null || !Str::contains($command, $excludes))) {
                return true;
            }
        }
        return false;
    }

    /**
     * Register seeds.
     *
     * @param string  $seeds_path
     * @return void
     */
    private function addSeedsFrom($path)
    {
        $files = glob( $path . '/*.php');
        foreach ($files as $filename)
        {
            $classes = $this->getClassesFromFile($filename);
            foreach ($classes as $class) {
                Artisan::call('db:seed', [ '--class' => $class, '--force' => '' ]);
            }
        }
    }

    /**
     * Get full class names declared in the specified file.
     *
     * @param string $filename
     * @return array an array of class names.
     */
    private function getClassesFromFile(string $filename) : array
    {
        // Get namespace of class (if vary)
        $namespace = "";
        $lines = file($filename);
        $namespaceLines = preg_grep('/^namespace /', $lines);
        if (is_array($namespaceLines)) {
            $namespaceLine = array_shift($namespaceLines);
            $match = array();
            preg_match('/^namespace (.*);$/', $namespaceLine, $match);
            $namespace = array_pop($match);
        }

        // Get name of all class has in the file.
        $classes = array();
        $php_code = file_get_contents($filename);
        $tokens = token_get_all($php_code);
        $count = count($tokens);
        for ($i = 2; $i < $count; $i++) {
            if ($tokens[$i - 2][0] == T_CLASS && $tokens[$i - 1][0] == T_WHITESPACE && $tokens[$i][0] == T_STRING) {
                $class_name = $tokens[$i][1];
                if ($namespace !== "") {
                    $classes[] = $namespace . "\\$class_name";
                } else {
                    $classes[] = $class_name;
                }
            }
        }

        return $classes;
    }
}
