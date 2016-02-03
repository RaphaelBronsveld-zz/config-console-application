<?php

namespace Raphaelb\Commands;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;

use Raphaelb\Foundation\Application;
use Raphaelb\Foundation\Artisan;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command {

    protected $items;

    /**
     * ConfigCommand constructor.
     */
    public function __construct()
    {
        $items = require __DIR__.'/../../config/test.php';
        $this->items = $items;

        parent::__construct();
    }

    /**
     * configure method.
     * Arguments / options added here.
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
     *
     * @return int|null|void
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

        $items = $this->items;
        $repo = new Repository($items);
        return $repo->get($key);

    }

    /**
     * set new Configvalue by given key and value.
     * And save the file.
     *
     * @param $key
     * @param $value
     *
     * @return int
     */
    protected function setConfigValue($key, $value){

        $items = $this->items;
        $path = $this->getPath();

        $repo = new Repository($items);
        $repo->set($key, $value);

        $array = new Collection($repo->items);
        $newArray = $array->items;

        $file = new Filesystem();
        return $file->put($path, '<?php return ' . var_export($newArray, true) . ' ?>');
    }

    /**
     * getPath method
     *
     * @return string
     */
    public function getPath()
    {
        return  __DIR__.'/../../config/test.php';
    }

}