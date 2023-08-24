<?php

namespace App\DataFixtures;

use App\Entity\Job;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class JobFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data = [
            "Data Scientist",
            "Statiticien",
            "Analyste Cyber-sécurité",
            "Médecin ORL",
            "Echographiste",
            "Mathématicien",
            "Ingénieur Logiciel",
            "Analyste Informatique",
            "Pathologiste du discours / langage",
            "Actuaire",
            "Ergothérapeute",
            "Directeur des ressources humaines",
            "Hygiéniste dentaire",
        ];
        for($i=0;$i<count($data);$i++)
        {
            $job = new Job();
            $job->setDesignation($data[$i]);
            $manager->persist($job);

        }

        $manager->flush();
    }
}
