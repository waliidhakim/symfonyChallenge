<?php

namespace App\Controller;

use App\Services\MailService;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FirstController extends AbstractController
{   private $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }


    #[Route('/email/send', name: 'app.email.send')]
    public function someAction()
    {
        $email = (new TemplatedEmail())
            ->from('idirwalidhakim31@gmail.com')
            ->to('idirwalidhakim32@gmail.com')
            ->subject('Subject')
            ->htmlTemplate('email/sample_email.html.twig')
            ->context(['name' => 'John Doe']);

        $this->mailService->sendEmail($email);

        // ...

        return $this->render('email/index.html.twig', [
            'controller_name' => 'EmailController',
        ]);
    }

    #[Route('/first', name: 'first')]
    public function index(): Response
    {
//        dd('je suis la requete /fisrt');
        return $this->render('first/index.html.twig', [
            'controller_name' => 'FirstController',

                'name' => "walid",
                'firstname' => "idir"

        ]);

        /*return new Response(
            content : "<head>
                            <title>First Controller </title>
                            <body>
                                <h1>Hello Symfony</h1>
                            </body>
                    </head>"
        );*/
    }

    #[Route('/template', name: 'template')]
    public function template(): Response
    {
//        dd('je suis la requete /fisrt');
        return $this->render('template.html.twig', [
            'controller_name' => 'FirstController',

            'name' => "walid"

        ]);

        /*return new Response(
            content : "<head>
                            <title>First Controller </title>
                            <body>
                                <h1>Hello Symfony</h1>
                            </body>
                    </head>"
        );*/
    }

//    #[Route('/sayHello/{name}/{firstname}', name: 'say.hello')]
    public function sayHello(Request $request,$name, $firstname): Response
    {
        //dd($request);
        return $this->render('first/sayHello.html.twig', [
            'nom' => $name,
            'prenom' => $firstname
        ]);

    }

    #[Route('/multi/{entier1</d+>}/{entier2</d+>}', name: 'multi')]
    public function multiplication($entier1, $entier2): Response
    {
        $result = $entier1*$entier2;
        return new Response("<h1>$result</h1>");

    }
}
