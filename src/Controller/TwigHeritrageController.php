<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TwigHeritrageController extends AbstractController
{
    #[Route('/twig', name: 'twig_heritrage')]
    public function index(): Response
    {
        return $this->render('twig_heritrage/index.html.twig', [
            'controller_name' => 'TwigHeritrageController',
        ]);
    }

    #[Route('/twig/heritage', name: 'heritage' )]
    public function heritage(): Response
    {
        return $this->render('heritage.html.twig', [
            'controller_name' => 'TwigHeritrageController',
        ]);
    }
}
