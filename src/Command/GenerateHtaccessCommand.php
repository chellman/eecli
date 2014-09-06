<?php

namespace eecli\Command;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;

class GenerateHtaccessCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'generate:htaccess';

    /**
     * {@inheritdoc}
     */
    protected $description = 'Generate the default ExpressionEngine .htaccess file';

    /**
     * {@inheritdoc}
     */
    protected function getArguments()
    {
        return array(
            array(
                'location',
                InputArgument::OPTIONAL,
                'Where to create the .htaccess file.',
            ),
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function fire()
    {
        // where to create the file, default to current directory
        $location = $this->argument('location') ?: '.';

        // make sure it has a trailing slash
        $location = rtrim($location, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR;

        $handlebars = new Handlebars(array(
            'loader' => new FilesystemLoader(__DIR__.'/../templates/'),
        ));

        $destination = $location.'.htaccess';

        if (file_exists($destination)) {
            $location = realpath($location).DIRECTORY_SEPARATOR;

            $confirmed = $this->confirm("An .htaccess file already exists in {$location}. Do you want to overwrite? [yN] ", false);

            if (! $confirmed) {
                $this->info('Did not create .htaccess file.');

                return;
            }
        }

        $handle = fopen($destination, 'w');

        $output = $handlebars->render('htaccess', array(
            'systemFolder' => $this->getApplication()->getSystemFolder(),
        ));

        fwrite($handle, $output);

        fclose($handle);

        $this->info($destination.' created.');
    }
}