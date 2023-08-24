<?php

namespace App\DataFixtures;

use App\Entity\Profile;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProfileFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
         $profile = new Profile();
         $profile->setRs('Facebook');
         $profile->setUrl('https://www.facebook.com/smythy.werbon');
         $manager->persist($profile);

        $profile2 = new Profile();
        $profile2->setRs('Instagram');
        $profile2->setUrl('https://www.instagram.com/idirwaliid/');
        $manager->persist($profile2);

        $profile3 = new Profile();
        $profile3->setRs('LinkedIn');
        $profile3->setUrl('https://www.linkedin.com/in/walid-idir-7259a522b/');
        $manager->persist($profile3);

        $profile4 = new Profile();
        $profile4->setRs('Github');
        $profile4->setUrl('https://github.com/waliidhakim');
        $manager->persist($profile4);

        $manager->flush();
    }
}
