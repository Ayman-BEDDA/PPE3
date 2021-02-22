<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request ;
use Symfony\Component\HttpFoundation\Response ;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType ;
use Symfony\Component\Form\Extension\Core\Type\PasswordType ;
use Symfony\Component\Form\Extension\Core\Type\SubmitType ;
use Symfony\Component\Form\Extension\Core\Type\ResetType ;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class RfController extends AbstractController
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
	            
	        $montTotal = 0 ;
	        
	        
	        $request = Request::createFromGlobals() ;                   
	                
			$form = $this->createFormBuilder(  )
				->add( 'ETP' , TextType::class , ['data' => 0] )
	                        ->add( 'KM' , TextType::class , ['data' => 0] )
	                        ->add( 'NUI' , TextType::class , ['data' => 0] )
	                        ->add( 'REP' , TextType::class , ['data' => 0] )
				->add( 'annuler' , ResetType::class )
	                        ->add( 'valider' , SubmitType::class )
				->getForm() ;
	                
			$form->handleRequest( $request ) ;
	 
			//if ( $form->isSubmitted() && $form->isValid() ) {
	                #if ( $form->getClickedButton() === $form->get('suivant') ) {
				$data = $form->getData() ;
	                        array( 'data' => $data ) ;
                                
                     /* $montETP = 110.00*$data['ETP'];
                        $montKM = 0.62*$data['KM'];
                        $montNUI = 80.00*$data['NUI'];
                        $montREP = 25.00*$data['REP'];
                        $montTotal = $montETP + $montKM + $montNUI + $montREP ;*/
                        /*$totalF = [ '1' => " nombre d'étapes : ".$data['ETP'] ,
                                    '2' => " nombre de kilometres : ".$data['KM'] ,
                                    '3' => " nombre de nuits : ".$data['NUI'] ,
                                    '4' => " nombre de repas : ".$data['REP'] ,
                                ];*/
                        
			$pdo = new \PDO('mysql:host=localhost; dbname=GSB_FRAIS', 'developpeur', 'azerty');
                                
                        $sqlw = $pdo->prepare("select quantite from LigneFraisForfait where idVisiteur = :id and mois = :mois");
                        $sqlw->bindParam(':id', $idV);
                        $sqlw->bindParam(':mois', $aaa);
                        $sqlw->execute();
                        $res = $sqlw->fetchAll(\PDO::FETCH_ASSOC);
                        $totalF = [ '1' => " nombre d'étapes : ".$res[0]['quantite'] ,
                                    '2' => " nombre de kilometres : ".$res[1]['quantite'] ,
                                    '3' => " nombre de nuits : ".$res[2]['quantite'] ,
                                    '4' => " nombre de repas : ".$res[3]['quantite'] , ];
                        $montETP = 110.00*$res[0]['quantite'];
                        $montKM = 0.62*$res[1]['quantite'];
                        $montNUI = 80.00*$res[2]['quantite'];
                        $montREP = 25.00*$res[3]['quantite'];
                        $montTotal = $montETP + $montKM + $montNUI + $montREP ;
                        #
                        $session->set('totalff',$montTotal) ;
                        #
                                
                                $sqlb = $pdo->prepare("select * from LigneFraisForfait where idVisiteur = :id and mois = :mois and idFraisForfait = 'ETP'") ;
                                $sqlb->bindParam(':id', $idV);
                                $sqlb->bindParam(':mois', $aaa);
                                $sqlb->execute() ;
				$check1 = $sqlb->fetch(\PDO::FETCH_ASSOC) ;
                                $count1 = $sqlb->rowCount() ;
                                if ( $count1 == 0 ) {
                                    $sql = $pdo->prepare("insert into LigneFraisForfait ( idVisiteur , mois , idFraisForfait , quantite ) values ( :id , :mois , 'ETP' , :quantite )") ;
                                    $sql->bindParam(':id', $idV);
                                    $sql->bindParam(':mois', $aaa);
                                    $sql->bindParam(':quantite', $data['ETP']);
                                }
                                else {
                                    $sql = $pdo->prepare("update LigneFraisForfait set quantite = :quantite where idVisiteur = :id and mois = :mois and idFraisForfait = 'ETP'") ;
                                    $add = $check1['quantite'] + $data['ETP'] ;
                                    $sql->bindParam(':quantite', $add);
                                    $sql->bindParam(':id', $idV);
                                    $sql->bindParam(':mois', $aaa);
                                }
                                
                                $sqlc = $pdo->prepare("select * from LigneFraisForfait where idVisiteur = :id and mois = :mois and idFraisForfait = 'KM'") ;
                                $sqlc->bindParam(':id', $idV);
                                $sqlc->bindParam(':mois', $aaa);
                                $sqlc->execute() ;
				$check2 = $sqlc->fetch(\PDO::FETCH_ASSOC) ;
                                $count2 = $sqlc->rowCount() ;
                                if ( $count2 == 0 ) {
                                    $sql2 = $pdo->prepare("insert into LigneFraisForfait ( idVisiteur , mois , idFraisForfait , quantite ) values ( :id , :mois , 'KM' , :quantite )") ;
                                    $sql2->bindParam(':id', $idV);
                                    $sql2->bindParam(':mois', $aaa);
                                    $sql2->bindParam(':quantite', $data['KM']);
                                }
                                else {
                                    $sql2 = $pdo->prepare("update LigneFraisForfait set quantite = :quantite where idVisiteur = :id and mois = :mois and idFraisForfait = 'KM'") ;
                                    $add2 = $check2['quantite'] + $data['KM'] ;
                                    $sql2->bindParam(':quantite', $add2);
                                    $sql2->bindParam(':id', $idV);
                                    $sql2->bindParam(':mois', $aaa);
                                }
                                
                                $sqld = $pdo->prepare("select * from LigneFraisForfait where idVisiteur = :id and mois = :mois and idFraisForfait = 'NUI'") ;
                                $sqld->bindParam(':id', $idV);
                                $sqld->bindParam(':mois', $aaa);
                                $sqld->execute() ;
				$check3 = $sqld->fetch(\PDO::FETCH_ASSOC) ;
                                $count3 = $sqld->rowCount() ;
                                if ( $count3 == 0 ) {
                                    $sql3 = $pdo->prepare("insert into LigneFraisForfait ( idVisiteur , mois , idFraisForfait , quantite ) values ( :id , :mois , 'NUI' , :quantite )") ;
                                    $sql3->bindParam(':id', $idV);
                                    $sql3->bindParam(':mois', $aaa);
                                    $sql3->bindParam(':quantite', $data['NUI']);
                                }
                                else {
                                    $sql3 = $pdo->prepare("update LigneFraisForfait set quantite = :quantite where idVisiteur = :id and mois = :mois and idFraisForfait = 'NUI'") ;
                                    $add3 = $check3['quantite'] + $data['NUI'] ;
                                    $sql3->bindParam(':quantite', $add3);
                                    $sql3->bindParam(':id', $idV);
                                    $sql3->bindParam(':mois', $aaa);
                                }
                                
                                $sqle = $pdo->prepare("select * from LigneFraisForfait where idVisiteur = :id and mois = :mois and idFraisForfait = 'REP'") ;
                                $sqle->bindParam(':id', $idV);
                                $sqle->bindParam(':mois', $aaa);
                                $sqle->execute() ;
				$check4 = $sqle->fetch(\PDO::FETCH_ASSOC) ;
                                $count4 = $sqle->rowCount() ;
                                if ( $count4 == 0 ) {
                                    $sql4 = $pdo->prepare("insert into LigneFraisForfait ( idVisiteur , mois , idFraisForfait , quantite ) values ( :id , :mois , 'REP' , :quantite )") ;
                                    $sql4->bindParam(':id', $idV);
                                    $sql4->bindParam(':mois', $aaa);
                                    $sql4->bindParam(':quantite', $data['REP']);
                                }
                                else {
                                    $sql4 = $pdo->prepare("update LigneFraisForfait set quantite = :quantite where idVisiteur = :id and mois = :mois and idFraisForfait = 'REP'") ;
                                    $add4 = $check4['quantite'] + $data['REP'] ;
                                    $sql4->bindParam(':quantite', $add4);
                                    $sql4->bindParam(':id', $idV);
                                    $sql4->bindParam(':mois', $aaa);
                                }
                        
                        
                                
                        if ( $form->getClickedButton() === $form->get('valider') ) {              
				$sql->execute() ;
                                $sql2->execute() ;
                                $sql3->execute() ;
                                $sql4->execute() ;
                        }   
                        
                        return $this->render( 'rf/index.html.twig', [ 
                                 'formulaire' => $form->createView() ,
                                 'controller_name' => 'RfController',
                                 'idVisiteur' => $idV ,
                                 'data' => $data ,
                                 'prenomV' => $prenom ,
                                 'nomV' => $nom ,
                                 'total' => $montTotal ,
                                 'totalF' => $totalF ,
                                 'todaymy' => $todaymy ,

                        ]);  
                //}
 
                $totalF = [ '1' => " nombre d'étapes : 0" ,
                            '2' => " nombre de kilometres : 0" ,
                            '3' => " nombre de nuits : 0" ,
                            '4' => " nombre de repas : 0" ,
                                ];
                
                return $this->render( 'rf/index.html.twig', [
                        'formulaire' => $form->createView() ,
                        'idVisiteur' => $idV ,
                        'prenomV' => $prenom ,
                        'nomV' => $nom ,
                        'total' => $montTotal ,
                        'totalF' => $totalF ,
                        'todaymy' => $todaymy ,
                        ]);                                 
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
                                
}
}