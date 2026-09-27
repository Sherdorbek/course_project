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

        $manager->flush();

        
        $attributes = [
            'First Name' => AttributeTypeEnum::StringType,
            'Last Name' => AttributeTypeEnum::StringType,
            'Location' => AttributeTypeEnum::StringType,
            'Personal photo' =>AttributeTypeEnum::ImageType,
            'Phone Number'=>AttributeTypeEnum::StringType
        ];
        $personalCategory = $manager->getRepository(EntityAttributeCategory::class)->findOneBy(['id' => 1]);

        foreach ($attributes as $name => $type) {
            $newAttr = new AttributeCv();
            $newAttr->setCategory($personalCategory);
            $newAttr->setName($name);
            $newAttr->setType($type);
            $newAttr->setIsRemovable(false);
            $manager->persist($newAttr);
        }

        $manager->flush();
    }
}
