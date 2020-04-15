<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\DataFixtures\CityFixtures;
use App\Repository\CityRepository;
use App\DataFixtures\CustomFixtures;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends CustomFixtures implements DependentFixtureInterface
{
    const AMOUNT = 20;

    private $passwordEncoder;
    private $cities;
    private $cityRepository;
    private $manager;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        CityRepository $cityRepository,
        EntityManagerInterface $manager
    )
    {
        parent::__construct();

        $this->passwordEncoder = $passwordEncoder;
        $this->cityRepository = $cityRepository;
        $this->manager = $manager;
    }

    protected function beforeCreate() {
        $this->cities = $this->cityRepository->findAll();

        $user = new User();

        $createdAt = $this->faker->dateTime();
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

        $gender = rand(0, 1);

        $user
            ->setEmail('admin@test.com')
            ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'admin'
            ))
            ->setLastName($this->faker->lastName())
            ->setBirthDate($this->faker->dateTime())
            ->setGender($gender)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt)
            ->setCity($this->cities[rand(0, sizeof($this->cities) - 1)])
            ->setRoles(['ROLE_ADMIN'])
            ->setApiToken($this->generateToken())
        ;

        if ($gender === 0) {
            $user->setFirstName($this->faker->firstNameFemale());
        } else {
            $user->setFirstName($this->faker->firstNameMale());
        }

        $this->manager->persist($user);
    }

    protected function createObject()
    {
        $object = new User();

        $createdAt = $this->faker->dateTime();
        $updatedAt = $this->faker->dateTimeBetween($createdAt, 'now');

        $gender = rand(0, 1);

        $object
            ->setEmail($this->faker->email())
            ->setPassword($this->passwordEncoder->encodePassword(
                $object,
                'motdepasse'
            ))
            ->setLastName($this->faker->lastName())
            ->setBirthDate($this->faker->dateTime())
            ->setGender($gender)
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt)
            ->setCity($this->cities[rand(0, sizeof($this->cities) - 1)])
            ->setApiToken($this->generateToken())
        ;

        if ($gender === 0) {
            $object->setFirstName($this->faker->firstNameFemale());
        } else {
            $object->setFirstName($this->faker->firstNameMale());
        }

        return $object;
    }

    public function getDependencies()
    {
        return array(
            CityFixtures::class
        );
    }

    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}