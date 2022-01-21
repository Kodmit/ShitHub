<?php

namespace App\DataFixtures;

use App\Action\User\CreateUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Messenger\MessageBusInterface;

class UserFixtures extends Fixture
{
    private MessageBusInterface $commandBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function load(ObjectManager $manager)
    {
        $this->commandBus->dispatch(new CreateUser('johndoe', 'Test123456..'));
    }
}
