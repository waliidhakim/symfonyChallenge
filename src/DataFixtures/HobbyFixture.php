<?php

namespace App\DataFixtures;

use App\Entity\Hobby;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class HobbyFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $data =[
            "Yoga",
            "Cuisine",
            "Patisserie",
            "Photographie",
            "Blogging",
            "Lecture",
            "Apprendre une langue",
            "Construction Lego",
            "Dessin",
            "Coloriage",
            "Peinture",
            "Se lancer dans le tissage de tapis",
            "Créer des vêtements ou des Cosplay",
            "Fabriquer des bijoux",
            "Travailler le métal",
            "Décorer des galets",
            "faire des puzzles avec de plus en plus de pièces",
            "Créer des minatures (maisons, voitures, trains, bateaux...)",
            "Améliorer son espace de vie ",
            "Apprendre à jongler",
            "Faire partie d'un club de lecture",
            "Apprendre la programmation informatique",
            "En apprendre plus sur le survivalisme",
            "Créer une chaine Youtube",
            "Jouer au fléchettes",
            "Apprendre à chanter et à faire chanter",

        ];

        for($i=0;$i<count($data);$i++)
        {
            $hobby = new Hobby();
            $hobby->setDesignation($data[$i]);
            $manager->persist($hobby);

        }

        $manager->flush();
    }
}
