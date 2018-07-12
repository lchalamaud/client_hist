<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SayHelloCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('say:hello')
            ->setDescription('Set')
            ->setHelp('Lovely command saying hello')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the user.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Command : Say Hello',
            '===================',
            '',
        ]);
        $output->writeln('Hello '.$input->getArgument('name'));
    }
}