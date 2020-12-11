<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use \PDO;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;
use Doctrine\ORM\EntityManagerInterface;


use App\Entity\Visiteur;
use Symfony\Component\HttpFoundation\Response;

class FicheController extends AbstractController
{
    
    /**
     * 
     * @param int $id
     * @return Response
     * @throws type
     */
    
    public function index(int $id): Response
    {
        $visiteur=$this->getDoctrine()
            ->getRepository(Visiteur::class)
            ->find($id);   
        
        if (!$visiteur) {
            throw $this->createNotFoundException(
                'No product found for id '.$id
            );
        }
        
        
    
        return new Response('Check out this great product: '.$visiteur->getName());
    }
}
