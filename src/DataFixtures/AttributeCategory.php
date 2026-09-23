<?php

namespace App\DataFixtures;

use App\Entity\AttributeCategory as EntityAttributeCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AttributeCategory extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $all = ['Personal information', 'Certification', 'Domain Knowledge', 'Soft skill'];
        foreach ($all as $c) {
            $category = new EntityAttributeCategory();
            $category->setName($c);
            $manager->persist($category);
        }
        $manager->flush();
    }
}
