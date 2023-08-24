<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TabController extends AbstractController
{
    #[Route('/tab/{nb<\d+>?5}', name: 'tab')]
    public function index($nb): Response
    {
        $notes = [];
        for($i=0; $i<$nb;$i++)
        {
            $notes[$i] = rand(0,20);
        }
        return $this->render('tab/index.html.twig', [
            'notes' => $notes,
        ]);
    }

    #[Route('/tab/users', name: 'tab.users')]
    public function users(): Response
    {
        $users = [
            [
                'firstname' => 'walid',
                'lastname' => 'idir',
                'age' => '26'
            ],
            [
                'firstname' => 'hakim',
                'lastname' => 'Udyr',
                'age' => '23'
            ],
            [
                'firstname' => 'Toufi',
                'lastname' => 'Belam',
                'age' => '27'
            ],

        ];
        return $this->render('tab/users.html.twig',[
            'users' => $users
        ]);
    }
}
