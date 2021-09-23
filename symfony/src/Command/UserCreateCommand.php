<?php

namespace App\Command;

use App\Action\User\CreateUser;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Messenger\MessageBusInterface;

class UserCreateCommand extends Command
{
    protected static $defaultName = 'app:user:create';
    protected static $defaultDescription = 'Add a short description for your command';

    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
        parent::__construct(null);
    }

    protected function configure(): void
    {
        $this->setDescription('Create an user')
            ->addArgument('test', InputArgument::OPTIONAL, 'The Business ID');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $username = $io->ask('Enter a username');
        $password = $io->askHidden('Enter the password');

        $this->commandBus->dispatch(new CreateUser($username, $password));

        $io->success(sprintf('User "%s" created !', $username));

        return Command::SUCCESS;
    }
}
