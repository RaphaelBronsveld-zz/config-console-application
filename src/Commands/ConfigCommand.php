<?php

namespace Raphaelb\Commands;

use Sebwite\Support\Path;
use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Raphaelb\Foundation\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigCommand extends Command {

    /** @var Repository $items */
    protected $items = [];

    /**
     * ConfigCommand constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->loadConfiguration();
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

        foreach ( $fs->files(Application::getConfigPath()) as $file )
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
            $value = $this->validateValue($value);
            $output->writeln('<comment>' . $value . '</comment>');
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
    protected function validateValue($input){
        if(is_array($input)){
            $input = array_values($input);
            var_dump($input);
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
        return $this->items->get($key);
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
    protected function setConfigValue($key, $value)
    {
        $fs = new Filesystem();
        $filename = $fs->name($key);

        // Get the right config array.
        $array = $this->items[$filename];

        //Make sure we use a valid key and add it to the array.
        $key = substr(strstr($key, '.'), strlen('.'));
        if(is_array($array[$key]))
        {
            $array[$key][] = $value;
        } else {
            $array[$key] = $value;
        }

        return $fs->put(Application::getConfigPath() . DIRECTORY_SEPARATOR
            . $filename . '.php',
            '<?php return ' . var_export($array, true). ';');
    }
}