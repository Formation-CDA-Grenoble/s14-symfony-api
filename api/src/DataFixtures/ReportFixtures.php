<?php

namespace App\DataFixtures;

use App\Entity\Report;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\DataFixtures\CustomFixtures;
use App\DataFixtures\MessageFixtures;
use App\Repository\MessageRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ReportFixtures extends CustomFixtures implements DependentFixtureInterface
{
    const AMOUNT = 40;

    private $users;
    private $userRepository;
    private $messageRepository;

    public function __construct(UserRepository $userRepository, MessageRepository $messageRepository) {
        parent::__construct();

        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
    }

    protected function beforeCreate() {
        $this->users = $this->userRepository->findAll();
        $this->messages = $this->messageRepository->findAll();
    }

    protected function createObject() {
        $object = new Report();

        $object
            ->setCreatedAt($this->faker->dateTime())
            ->setReporter($this->users[rand(0, sizeof($this->users) - 1)])
            ->setReportedMessage($this->messages[rand(0, sizeof($this->messages) - 1)])
        ;

        return $object;
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class,
            MessageFixtures::class
        );
    }
}
