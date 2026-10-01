<?php

namespace App\Command;

use App\Entity\AttributeCategory;
use App\Entity\AttributeCv;
use App\Entity\User;
use App\Enum\AttributeTypeEnum;
use App\Enum\UserRoleEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:seed',
    description: 'Insert initial data to DB',
)]
class SeedCommand
{
    public function __invoke(
        SymfonyStyle $io,
        EntityManagerInterface $manager
    ): int {

        $all = ['Personal information', 'Certification', 'Domain Knowledge', 'Soft skill'];
        foreach ($all as $c) {
            $category = new AttributeCategory();
            $category->setName($c);
            $manager->persist($category);
        }

        $manager->flush();


        $attributes = [
            'First Name' => AttributeTypeEnum::StringType,
            'Last Name' => AttributeTypeEnum::StringType,
            'Location' => AttributeTypeEnum::StringType,
            'Personal photo' => AttributeTypeEnum::ImageType,
            'Phone Number' => AttributeTypeEnum::StringType
        ];
        $personalCategory = $manager->getRepository(AttributeCategory::class)->findOneBy(['id' => 1]);

        foreach ($attributes as $name => $type) {
            $newAttr = new AttributeCv();
            $newAttr->setCategory($personalCategory);
            $newAttr->setName($name);
            $newAttr->setType($type);
            $newAttr->setIsRemovable(false);
            $manager->persist($newAttr);
        }

        $admin = new User();

        $admin->setEmail('admin@test.com');
        $admin->setRole(UserRoleEnum::Admin);
        $admin->setProfileSetUp(false);
        $admin->setLocale('en');
        $admin->setTheme('light');
        $admin->setPassword(bin2hex('password'));

        $manager->persist($admin);
        $candidate = new User();

        $candidate->setEmail('candidate@test.com');
        $candidate->setRole(UserRoleEnum::Candidate);
        $candidate->setProfileSetUp(false);
        $candidate->setLocale('en');
        $candidate->setTheme('light');
        $candidate->setPassword(bin2hex('password'));

        $manager->persist($candidate);
        $recruiter = new User();

        $recruiter->setEmail('recruiter@test.com');
        $recruiter->setRole(UserRoleEnum::Recruiter);
        $recruiter->setProfileSetUp(false);
        $recruiter->setLocale('en');
        $recruiter->setTheme('light');
        $recruiter->setPassword(bin2hex('password'));

        $manager->persist($recruiter);

        $manager->flush();
        return Command::SUCCESS;
    }
}
