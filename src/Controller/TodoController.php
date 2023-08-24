<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/todo')]
class TodoController extends AbstractController
{
    #[Route('/', name: 'todo')]
    public function index(Request $request): Response
    {
        // si j'ai un tableau de todo alors je l'affiche directement
        //sinon je le crée puis je l'affiche

        $session = $request->getSession();

        if(!$session->has('todos')){
            $todos = [
                'achat' => 'Acheter clé USB',
                'cours' => 'Finaliser mon cours',
                'correction' => 'Corriger mes examens'
            ];
            $session->set('todos', $todos);
            $this->addFlash("info", "La liste de todos vient d'être initialisé");
        }


        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
        ]);
    }

    #[Route(
        '/add/{cle?test}/{valeur?test}',
        name : 'todo.add'
        /*defaults : ['valeur' => 'sport']*/
    )]
    public function addTodo(Request $request,  $cle, $valeur) : RedirectResponse
    {
        $session = $request->getSession();

        if($session->has('todos'))
        {
            $todos = $session->get('todos');

            if(isset($todos[$cle])){
                $this->addFlash("error", "Le todos d'id $cle existe déjà");
            }else{
                $todos[$cle] = $valeur;
                $session->set('todos',$todos);
                $this->addFlash("success", "Le todos d'id $cle a été ajouté avec succès");
            }
        }else {
            $this->addFlash("error", "La liste de todos n'est pas encore initialisé");

        }
        return $this->redirectToRoute('todo');
    }


    #[Route('/update/{cle}/{valeur}',name : 'todo.update')]
    public function updateTodo(Request $request,  $cle, $valeur): RedirectResponse
    {
        $session = $request->getSession();

        if($session->has('todos'))
        {
            $todos = $session->get('todos');

            if(!isset($todos[$cle])){
                $this->addFlash("error", "Le todos d'id $cle n'existe pas");
            }else{
                $todos[$cle] = $valeur;
                $session->set('todos',$todos);
                $this->addFlash("success", "Le todos d'id $cle a été modifié avec succès");
            }
        }else {
            $this->addFlash("error", "La liste de todos n'est pas encore initialisé");

        }
        return $this->redirectToRoute('todo');
    }

    #[Route('/delete/{cle}',name : 'todo.delete')]
    public function deleteTodo(Request $request,  $cle): RedirectResponse
    {
        $session = $request->getSession();

        if($session->has('todos'))
        {
            $todos = $session->get('todos');

            if(!isset($todos[$cle])){
                $this->addFlash("error", "Le todos d'id $cle n'existe pas");
            }else{
                unset($todos[$cle]);
                $session->set('todos',$todos);
                $this->addFlash("success", "Le todos d'id $cle a été supprimé avec succès");
            }
        }else {
            $this->addFlash("error", "La liste de todos n'est pas encore initialisé");

        }
        return $this->redirectToRoute('todo');
    }


    #[Route('/reset',name : 'todo.reset')]
    public function resetTodo(Request $request): RedirectResponse
    {
        $session = $request->getSession();
        $session->remove('todos');

        return $this->redirectToRoute('todo');
    }
}
