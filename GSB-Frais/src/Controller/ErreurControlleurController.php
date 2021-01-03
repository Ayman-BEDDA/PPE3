<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErreurControlleurController extends AbstractController
{
    /**
     * @Route("/erreur/controlleur", name="erreur_controlleur")
     */
    public function index(): Response
    {
        return $this->render('erreur_controlleur/index.html.twig', [
            'controller_name' => 'ErreurControlleurController',
        ]);
    }
}
