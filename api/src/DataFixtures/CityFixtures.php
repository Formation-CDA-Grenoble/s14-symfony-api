<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\DataFixtures\CustomFixtures;

class CityFixtures extends CustomFixtures
{
    const AMOUNT = 20;

    protected function createObject() {
        $object = new City();

        $object
            ->setName($this->faker->city())
            ->setLatitude($this->faker->latitude())
            ->setLongitude($this->faker->longitude())
        ;

        return $object;
    }
}
