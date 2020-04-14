<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

abstract class CustomFixtures extends Fixture
{
    protected $faker;

    public function __construct() {
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {
        if (!defined('static::AMOUNT')) {
            throw new \RunTimeException('Constant AMOUNT not set in ' . get_called_class());
        }

        $this->beforeCreate();

        for ($i = 0; $i < static::AMOUNT; $i += 1) {
            $object = $this->createObject();
            $manager->persist($object);
        }

        $manager->flush();
    }

    protected function createRandomPicture(int $width = 480, int $height = 480) {
        return 'https://i.picsum.photos/id/' . rand(0, 1000) . '/' . $width . '/' . $height . '.jpg';
    }

    protected function beforeCreate() { }

    abstract protected function createObject();
}
