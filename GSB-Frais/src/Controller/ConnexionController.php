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

class ConnexionController extends AbstractController{

    
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
                
                $rqt = $pdo->prepare("select * from Visiteur INNER JOIN FicheFrais ON Visiteur.id = FicheFrais.idVisiteur INNER JOIN Etat ON Etat.id = FicheFrais.idEtat where login = :identifiant") ;
                $rqt->bindParam(':identifiant', $data['identifiant']);
                $rqt->execute() ;
                $resultat1 = $rqt->fetch(\PDO::FETCH_ASSOC) ;
               
                $rqt3 =  $pdo->prepare("select * from Visiteur where login = :identifiant") ;
                $rqt3->bindParam(':identifiant', $data['identifiant']);
                $rqt3->execute() ;
                $resultat12 = $rqt3->fetch(\PDO::FETCH_ASSOC) ;
               
                $sql = $pdo->prepare("select mdp from Visiteur where mdp = :motDePasse") ;
                $sql->bindParam(':motDePasse', $data['motDePasse']);
                $sql->execute() ;
                $resultat2 = $sql->fetch(\PDO::FETCH_ASSOC) ;
               
               
                $iduser = $resultat1['id'];
                $session2=$request->getSession();
                    $session2->set('id',$resultat1['id']);
                    $session2->get('id');
                   
                $libelleuser = $resultat1['libelleEtat'];
                $session3=$request->getSession();
                    $session3->set('libelleEtat',$resultat1['libelleEtat']);
                    $session3->get('libelleEtat');
                 
               
               
               
                $rqt2 = $pdo->prepare("select * from Visiteur INNER JOIN LigneFraisForfait ON Visiteur.id = LigneFraisForfait.idVisiteur INNER JOIN FraisForfait ON FraisForfait.id = LigneFraisForfait.idFraisForfait where login = :identifiant") ;
                $rqt2->bindParam(':identifiant', $data['identifiant']);
                $rqt2->execute() ;
                $resultat3 = $rqt2->fetch(\PDO::FETCH_ASSOC) ;
               
                $add = $resultat3['montant'] * $resultat3['quantite'];    
                $montantUser = $add;
                $session4=$request->getSession();
                    $session4->set('montant',$add);
                    $session4->get('montant');
               
                   
                $libelleFraisUser = $resultat3['libelle'];    
                $session5=$request->getSession();
                    $session5->set('libelle',$resultat3['libelle']);
                    $session5->get('libelle');
                 var_dump($libelleFraisUser);  
               
                $quantiteUser = $resultat3['quantite'];    
                $session6=$request->getSession();
                    $session6->set('quantite',$resultat3['quantite']);
                    $session6->get('quantite');

                
                
                
                if ( $resultat12['login'] == $data['identifiant'] && $resultat2['mdp'] == $data['motDePasse'] ) {
                    $session=$request->getSession();
                    $session->set('login',$data['identifiant']);
                    $session->get('login');
                    
                    $session->set('id',$resultat12['id']);
                    $session->get('id');
                    
                    $session->set('nom',$resultat12['nom']);
                    $session->get('nom');
                    
                    $session->set('prenom',$resultat12['prenom']);
                    $session->get('prenom');
                    
                    return $this->redirectToRoute( 'affichage', array( 'data' => $data ) ) ;
                    }
                else {
                    return $this->redirectToRoute( 'erreur_controlleur', array( 'data' => $data ) ) ;
                }
                
        }       
        return $this->render( 'connexion/index.html.twig', array( 'formulaire' => $form->createView() ) ) ;
    
    }
   
}