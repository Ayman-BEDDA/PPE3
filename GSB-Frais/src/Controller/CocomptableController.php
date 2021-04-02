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
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class CocomptableController extends AbstractController{

    
   public function index(Request $request)
    {
        
        
        
        
        
        $form = $this->createFormBuilder(  )
            ->add( 'identifiant' , TextType::class )
            ->add( 'motDePasse' , PasswordType::class )
            ->add( 'valider' , SubmitType::class )
            ->add( 'effacer' , ResetType::class )
            ->getForm() ;
            
        $form->handleRequest( $request ) ;
        
        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData() ;
           
                array( 'data' => $data ) ;
                
                $pdo = new \PDO('mysql:host=localhost; dbname=GSB_FRAIS', 'developpeur', 'azerty');
                
                $rqt = $pdo->prepare("select * from Comptable where login = :identifiant") ;
                $rqt->bindParam(':identifiant', $data['identifiant']);
                $rqt->execute() ;
                $resultat1 = $rqt->fetch(\PDO::FETCH_ASSOC) ;
                
                
                $sql = $pdo->prepare("select * from Comptable where mdp = :motDePasse") ;
                $sql->bindParam(':motDePasse', $data['motDePasse']);
                $sql->execute() ;
                $resultat2 = $sql->fetch(\PDO::FETCH_ASSOC) ;
                
                
                
                if ( $resultat1['login'] == $data['identifiant'] && $resultat2['mdp'] == $data['motDePasse'] ) {
                    $session=$request->getSession();
                    $session->set('loginc',$data['identifiant']);
                    $session->get('loginc');
                    
                    $session->set('idc',$resultat1['id']);
                    $session->get('idc');
                    
                    $session->set('nomc',$resultat1['nom']);
                    $session->get('nomc');
                    
                    $session->set('prenomc',$resultat1['prenom']);
                    $session->get('prenomc');
                    
                    return $this->redirectToRoute( 'affichec', array( 'data' => $data ) ) ;
                    }
                else {
                    return $this->redirectToRoute( 'erreur_controlleur', array( 'data' => $data ) ) ;
                }
                
        }       
        return $this->render( 'cocomptable/index.html.twig', array( 'cocomptable' => $form->createView() ) ) ;
    
    }
}