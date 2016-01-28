<?php

namespace Raphaelb\Console;

use Illuminate\Config\Repository;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Illuminate\Filesystem\Filesystem;

class ConfigCommand extends Command {


    /**
     * configure method
     */
    protected function configure()
    {
        $this->setName('config')
             ->setDescription('Get or set Config value\'s by key.')
             ->addArgument('configkey',
                            InputArgument::REQUIRED,
                            'Config key --get to get value. Or Config key --set to mess around with value\'s.'
             )
             ->addOption('get')
             ->addOption(
                 'set',
                 'null',
                 InputOption::VALUE_REQUIRED,
                'Which key to what value?'
             );
    }

    /**
     * execute method
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $key = $input->getArgument('configkey');
        $optionget = $input->getOption('get');
        $optionset = $input->getOption('set');


        if($key && $optionget){
            $value = $this->getConfigValue($key);
            $output->writeln('<comment>' . $value . '</comment>');
        }

        if($key && $optionset){
            $this->setConfigValue($key, $optionset);
            $output->writeln('New value is set to: ' . $optionset);
        }


    }

    /**
     * getConfigValue method to return value by given key.
     *
     * @param $key
     *
     * @return mixed
     */
    protected function getConfigValue($key) {

        $items = require __DIR__.'/../../config/app.php';
        $repo = new Repository($items);
        return $repo->get($key);

    }

    protected function setConfigValue($key, $value){

        $items = require __DIR__.'/../../config/app.php';
        $path =  __DIR__.'/../../config/app.php';

        $repo = new Repository($items);
        $repo->set($key, $value);

        $array = new Collection($repo->items);
        $newArray = $array->items;

        $file = new Filesystem();
        return $file->put($path, '<?php return ' . var_export($newArray, true) . ' ?>');
    }

}