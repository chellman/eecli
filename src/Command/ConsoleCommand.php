<?php

namespace eecli\Command;

use Illuminate\Console\Command;
use Boris\Boris;
use eecli\Command\Contracts\HasLongDescription;

class ConsoleCommand extends Command implements HasLongDescription
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'console';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Start an interactive console.';

    /**
     * {@inheritdoc}
     */
    protected function fire()
    {
        $requiredExtensions = array('readline', 'posix', 'pcntl');

        foreach ($requiredExtensions as $extension) {
            if (! extension_loaded($extension)) {
                throw new \Exception(sprintf('PHP %s extension is required for this command.', $extension));
            }
        }

        $boris = new Boris('> ');

        $boris->start();
    }

    /**
     * {@inheritdoc}
     */
    public function getLongDescription()
    {
        return <<<EOT
Start a interactive console.

![Screencast of interactive console](https://github.com/rsanchez/eecli/wiki/images/console.gif)
EOT;
    }
}