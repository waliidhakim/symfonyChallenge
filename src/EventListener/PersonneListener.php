<?php

namespace App\EventListener;

use App\Event\AddPersonneEvent;
use App\Event\ListAllPersonneEvent;
use Psr\Log\LoggerInterface;

class PersonneListener
{
    public function __construct(private LoggerInterface $logger){

    }

    public function onAddPersonneListener(AddPersonneEvent $event){
        //dd(sprintf("Je suis en train d'écouter l'évenement : %s", AddPersonneEvent::ADD_PERSONNE_EVENT));
        $this->logger->debug(sprintf("Je suis en train d'écouter l'évenement : %s et la personne %s vient d'être ajouté",
            AddPersonneEvent::ADD_PERSONNE_EVENT,
                    $event->getPersonne()->getName()
        ));
    }


    public function onListAllPersonne(ListAllPersonneEvent $event){
        $this->logger->debug(sprintf("Je suis en train d'écouter l'évenement : %s et le nombre de personnes dans la base est :  %s",
            ListAllPersonneEvent::LIST_ALL_PERSONNE_EVENT,
            $event->getNbPersonne()
        ));
    }


    public function onListAllPersonne2(ListAllPersonneEvent $event){
        $this->logger->debug(sprintf("le second Listener"));
    }



}