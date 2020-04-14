<?php

namespace App\DataFixtures;

use App\Entity\Picture;
use App\DataFixtures\UserFixtures;
use App\Repository\UserRepository;
use App\DataFixtures\CustomFixtures;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PictureFixtures extends CustomFixtures implements DependentFixtureInterface
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

    protected function createObject()
    {
        $object = new Picture();

        $user = $this->users[rand(0, sizeof($this->users) - 1)];

        $object
            ->setUrl($this->createRandomPicture())
            ->setCreatedAt($this->faker->dateTime())
            ->setUserGallery($user)
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