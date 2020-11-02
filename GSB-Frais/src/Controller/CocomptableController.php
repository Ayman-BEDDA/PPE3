<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use \PDO;


class CocomptableController extends AbstractController{

    
   public function index(Request $request)
    {
        
        $request = Request::createFromGlobals() ;
                
        $form = $this->createFormBuilder(  )
            ->add( 'identifiant' , TextType::class )
            ->add( 'motDePasse' , PasswordType::class )
            ->add( 'valider' , SubmitType::class )
            ->add( 'annuler' , ResetType::class )
            ->getForm() ;
            
        $form->handleRequest( $request ) ;
        
        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData() ;
            $session=$request->getSession();
            $session=set('identifiant','a131');
                array( 'data' => $data ) ;
                
                $pdo = new \PDO('mysql:host=localhost; dbname=GSB_FRAIS', 'developpeur', 'azerty');
                
                $rqt = $pdo->prepare("select * from Visiteur where login = :identifiant") ;
                $rqt->bindParam(':identifiant', $data['identifiant']);
                $rqt->execute() ;
                $resultat1 = $rqt->fetch(\PDO::FETCH_ASSOC) ;
                
                $sql = $pdo->prepare("select * from Visiteur where mdp = :motDePasse") ;
                $sql->bindParam(':motDePasse', $data['motDePasse']);
                $sql->execute() ;
                $resultat2 = $sql->fetch(\PDO::FETCH_ASSOC) ;
                
                if ( $resultat1['login'] == $data['identifiant'] && $resultat2['mdp'] == $data['motDePasse'] ) {
                    return $this->redirectToRoute( 'affichage', array( 'data' => $data ) ) ;
                    }
                else {
                    return $this->redirectToRoute( 'cocomptable', array( 'data' => $data ) ) ;
                }
    
        }       
        return $this->render( 'cocomptable/index.html.twig', array( 'comptable' => $form->createView() ) ) ;
    
    }
   
}