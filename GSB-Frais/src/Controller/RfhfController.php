<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class RfhfController extends AbstractController
{
    public function index( Request $test )
{

        #Session
        $session = $test->getSession() ;
        $idV = $session->get( 'id' ) ;
        $prenom = $session->get( 'prenom' ) ;
        $nom = $session->get( 'nom' ) ;
        
        #Date
        $today = getdate() ;
        $todayMonth = $today['mon'] ;
        $todayYear = $today['year'] ;
        $todaymy = $todayMonth."-".$todayYear ;
        $auj = date('Y-m-d') ;
        if( strlen($todayMonth) != 2 ){
            $todayMonth = 0 . $todayMonth ;
        }
        $aaa = sprintf("%02d%04d",$todayMonth,$todayYear) ;      
        
        $request = Request::createFromGlobals() ;                   
                
		$form = $this->createFormBuilder(  )
                        ->add( 'dateEngagement' , TextType::class , ['data' => $auj] )
                        ->add( 'libelle' , TextType::class )
                        ->add( 'montant' , TextType::class )
			->add( 'annuler' , ResetType::class )
                        ->add( 'valider' , SubmitType::class )
			->getForm() ;
                
                /*$form2 = $this->createFormBuilder(  )
                        ->add( 'Supprimer' , SubmitType::class )
			->getForm() ;*/
                
		$form->handleRequest( $request ) ;
                //$form2->handleRequest( $request ) ;
                
                #instant unless afficher après un button submit
                //if ( $form->isSubmitted() && $form->isValid() ) {   
			$data = $form->getData() ;
                        array( 'data' => $data ) ;
                        
                        $pdo = new \PDO('mysql:host=localhost; dbname=GSB_FRAIS', 'developpeur', 'azerty');
				
				$sql = $pdo->prepare("insert into LigneFraisHorsForfait ( idVisiteur , mois , libelle , date , montant ) values ( :identifiant , :moisAnnee , :libelle , :date , :montant )") ;
				$sql->bindParam(':identifiant', $idV);
                                $sql->bindParam(':moisAnnee', $aaa);
                                $sql->bindParam(':libelle', $data['libelle']);
                                $sql->bindParam(':date', $data['dateEngagement']);
                                $sql->bindParam(':montant', $data['montant']);
                                
                                $sql2 = $pdo->prepare("select * from LigneFraisHorsForfait where idVisiteur = :id") ;
                                $sql2->bindParam(':id', $idV);
                                $sql2->execute() ;
				$tab = $sql2->fetchAll(\PDO::FETCH_ASSOC) ;
                                
                                //$sql3 = "select * from LigneFraisHorsForfait where idVisiteur = :id";
                                
                                $res = $pdo->query("select count(*) from LigneFraisHorsForfait where idVisiteur = '$idV'");
                                $nbLigneRes = $res->fetchColumn();
                                $nbLigneRes = $nbLigneRes - 1 ;
                                
                                
                                
                                if ( $form->getClickedButton() === $form->get('valider') ) {              
                                    $sql->execute() ;
                                    
                                }                              
                                
                                return $this->render( 'rfhf/index.html.twig', [ 
                                 'formulaire' => $form->createView() ,
                                 //'formulaire2' => $form2->createView() ,  
                                 'controller_name' => 'RfhfController',
                                 'idVisiteur' => $idV ,
                                 'data' => $data ,
                                 'prenomV' => $prenom ,
                                 'nomV' => $nom ,
                                 'todaymy' => $todaymy ,
                                 'tab' => $tab ,
                                 'nbLigne' => $nbLigneRes ,   
                        ]);
              //  }
    
                /*if ( $form2->isSubmitted() && $form2->isValid() ) { 
                    if ( $form2->getClickedButton() === $form2->get('Supprimer') ) { 
                        return $this->redirectToRoute( 'visiteur/renseigner/fhf/confirmation', []);
                    }
                }*/
                
                
                $data = 
                [
                 [  'dateEngagement' => null ,
                    'libelle' => null ,
                    'montant' => null ,  ] ,
                 [  'dateEngagement' => null ,
                    'libelle' => null ,
                    'montant' => null ,  ] ,
                 [  'dateEngagement' => null ,
                    'libelle' => null ,
                    'montant' => null ,  ] ,  
                ] ;
                
                $tab = [
                    [
                    'id' => 0 ,    
                    'montant' => null ,
                    'date' => null ,
                    'libelle' => null ,
                    ] ,
                    [
                    'id' => 0 ,    
                    'montant' => null ,
                    'date' => null ,
                    'libelle' => null ,
                    ]
                    ] ;
                $nbLigneRes = 0 ;
                
                return $this->render( 'rfhf/index.html.twig', [
                        'formulaire' => $form->createView() ,
                        //'formulaire2' => $form2->createView() ,
                        'idVisiteur' => $idV ,
                        'prenomV' => $prenom ,
                        'nomV' => $nom ,
                        'todaymy' => $todaymy ,
                        'data' => $data ,
                        'tab' => $tab ,
                        'nbLigne' => $nbLigneRes ,
                        ]); 
    }        


}