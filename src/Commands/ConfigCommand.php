<?php

namespace Raphaelb\Commands;

use Illuminate\Console\Command;
use Sebwite\Support\Path;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command {

    /** @var Repository $items */
    protected $items = [];

    protected $dir = __DIR__;

    protected $description = 'Get or set config value \'s by given key';
    /**
     * ConfigCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadConfiguration();
    }

    public function getConfigDir(){
        return $this->dir . '/../../config';
    }

    /**
     * loadConfiguration method
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function loadConfiguration()
    {
        /** @var \Illuminate\Filesystem\Filesystem $fs */
        $fs     = new Filesystem();
        $items = new Repository();

        foreach ( $fs->files($this->getConfigDir()) as $file )
        {
            $items->set(
                Path::getFilenameWithoutExtension($file),
                $fs->getRequire($file)
            );
        }
        $this->items = $items;
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
                'Config key. Array dot notation possible.'
            )
            ->addArgument('configvalue',
                InputArgument::OPTIONAL,
                'Config value. Give key to set new value.'
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
        $optionset = $input->getArgument('configvalue');


        if($key){
            $value = $this->getConfigValue($key);
            $value = $this->printableArray($value);
            $output->writeln('<info>' . $value . '</info>');
        }

        if($key && $optionset){
            $this->setConfigValue($key, $optionset);
            $output->writeln('New value is set to: ' . $optionset);
        }
    }

    /**
     * validateValue method
     * Is the input value an array or just a string?
     * @param $input
     *
     * @return mixed
     */
    protected function printableArray($input){
        if(is_array($input)){
            print_r(array_values($input));
        }
        else {
            return $input;
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
        $results = $this->items->get($key);

        if($results){
            return $results;
        } else {
            $value = 'test222';
            return $this->setConfigValue($key, $value);
        }
    }

    /**
     * set new Config value by given key and value.
     * And save the file.
     *
     * @param $key
     * @param $value
     *
     * @return int
     */
    protected function setConfigValue($key, $value)
    {
        $fs = new Filesystem();
        $fn = $fs->name($key);

        // First string until dot should be the file name.
        $filename =strstr($fn, '.', true);

        if(strpos($filename, '.') !== false) {
           $filename = $fn;
        }

        $this->items->set($key, $value);

        return $fs->put($this->getConfigDir() . DIRECTORY_SEPARATOR
            . $filename . '.php',
            '<?php return ' . var_export($this->items[$fn], true). ';');
    }
}