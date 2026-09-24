<?php

namespace App\DataFixtures;

use App\Entity\AttributeCategory as EntityAttributeCategory;
use App\Entity\AttributeCv;
use App\Enum\AttributeTypeEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
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

        $attributes = ['First Name','Last Name','Location','Personal photo','Email','Phone Number'];
        foreach ($attributes as $attribute) {
            $newAttr = new AttributeCv();
            $newAttr->setCategory($manager->getRepository(EntityAttributeCategory::class)->findOneBy(['name'=>'Personal information']));
            $newAttr->setName($attribute);
            $newAttr->setType(AttributeTypeEnum::StringType);
            $newAttr->setIsRemovable(false);
            $manager->persist($newAttr);
        }

        $manager->flush();
    }
}
