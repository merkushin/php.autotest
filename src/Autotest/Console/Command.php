<?php
namespace Autotest\Console;

use Autotest\Autotest;
use Autotest\Config;
use Symfony\Component\Console\Command\Command as ConsoleCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends ConsoleCommand
{
    protected function configure()
    {
        $this->setName('php.autotest');
        $this->setDescription('Run phpunit tests automatically');

        $this->addOption(
            'cmd',
            null,
            InputOption::VALUE_REQUIRED,
            'Custom path to PHPUnit executable'
        );

        $this->addOption(
            'src_path',
            null,
            InputOption::VALUE_REQUIRED,
            'Custom path to source code'
        );

        $this->addOption(
            'tests_path',
            null,
            InputOption::VALUE_REQUIRED,
            'Custom path to the tests directory'
        );

        $this->addOption(
            'suffix',
            null.
            InputOption::VALUE_OPTIONAL
        );

        $this->addOption(
            'timeout',
            null,
            InputOption::VALUE_OPTIONAL
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = new Config(getcwd());
        $config->setOptions($input->getOptions());

        $autotest = new Autotest($config);
        $autotest->run();
    }
}