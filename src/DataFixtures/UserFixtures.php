<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $hasher){

    }
    public function load(ObjectManager $manager): void
    {
        $admin1 = new User();
        $admin1->setEmail('idirwalidhakim32@gmail.com');
        $admin1->setFirstname("Walid");
        $admin1->setLastname("idir");
        $admin1->setIsVerified(true);
        $admin1->setPassword($this->hasher->hashPassword($admin1,'123456'));
        $admin1->setRoles(['ROLE_USER','ROLE_ADMIN']);


        $admin2 = new User();
        $admin2->setEmail('smythywerbon@gmail.com');
        $admin2->setFirstname("Walid");
        $admin2->setLastname("idir");
        $admin2->setIsVerified(true);
        $admin2->setPassword($this->hasher->hashPassword($admin2,'123456'));
        $admin2->setRoles(['ROLE_USER','ROLE_ADMIN']);


        $manager->persist($admin1);
        $manager->persist($admin2);

        for($i=1;$i<=5;$i++)
        {
            $user = new User();
            $user->setEmail("user$i@gmail.com");
            $user->setPassword($this->hasher->hashPassword($user,'user'));
            $manager->persist($user);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        // TODO: Implement getGroups() method.
        return ['user'];
    }
}
