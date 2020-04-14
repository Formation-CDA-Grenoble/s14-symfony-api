<?php

namespace App\DataFixtures;

use App\Entity\Heart;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\DataFixtures\CustomFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class HeartFixtures extends CustomFixtures implements DependentFixtureInterface
{
    const AMOUNT = 100;

    private $users;
    private $userRepository;

    public function __construct(UserRepository $userRepository) {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    protected function beforeCreate() {
        $this->users = $this->userRepository->findAll();
    }

    protected function createObject() {
        $object = new Heart();

        // Mélange la collection d'utilisateurs
        shuffle($this->users);
        // Récupère les deux premiers utilisateurs de la collection

        $object
            ->setCreatedAt($this->faker->dateTime())
            ->setSender($this->users[0])
            ->setRecipient($this->users[1])
        ;

        return $object;
    }

    public function getDependencies()
    {
        return array(
            UserFixtures::class
        );
    }
}
