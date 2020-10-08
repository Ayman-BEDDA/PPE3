<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class AccueilController extends AbstractController
{
    
    public function index()
    {
        return $this->render('accueil/index.html.twig', [
           
        ]);
    }
}
