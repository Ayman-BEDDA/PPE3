<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use \PDO;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class ConsulterController extends AbstractController
{
    
    public function index(Request $request)
    {
        
        
        
        
                
        $form = $this->createFormBuilder(  )
            ->add( 'mois' , ChoiceType::class,[
    'choices' => [
        'janvier' => 01,
        'fevrier' => 02,
        'mars' => 03,
        'avril' => 04,
        'mai'=>05,
        'juin' => 06,
        'juillet' => 07,
        'août' => 8,
        'septembre' => 9,
        'octobre' => 10,
        'novembre' => 11,
        'decembre' => 12]])
                
            ->add( 'annee' , ChoiceType::class,[
    'choices' => [
        '2015'=>2015,
        '2016'=>2016,
        '2017'=>2017,
        '2018'=>2018,
        '2019'=>2019,
        '2020'=>2020,
        '2021'=>2021,
        '2022'=>2022]])
        
        
            ->add( 'valider' , SubmitType::class )
            ->add( 'annuler' , ResetType::class )
            ->getForm() ;
            
        $form->handleRequest( $request ) ;
        
        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData() ;
               
                array( 'data' => $data ) ;
                $pdo = new \PDO('mysql:host=localhost; dbname=GSB_FRAIS', 'developpeur', 'azerty');
                
                $rqt = $pdo->prepare("select * from Visiteur where login = :identifiant") ;
                $rqt->bindParam(':identifiant', $data['identifiant']);
                $rqt->execute() ;
                $resultat2 = $rqt->fetch(\PDO::FETCH_ASSOC) ;
                
                
                $format="%s%s";
                $date= sprintf($format,$data['mois'],$data['annee']);
                $pdo = new \PDO('mysql:host=localhost; dbname=GSB_FRAIS', 'developpeur', 'azerty');
                $rqt = $pdo->prepare("select * from FicheFrais INNER JOIN Visiteur ON FicheFrais.idVisiteur=Visiteur.id where mois = $date") ;
                
                $session=$request->getSession();
                $session->set('mois',$data['mois']);
                $session->get('mois');
                
                $session->set('annee',$data['annee']);
                $session->get('annee');
                
                $rqt->execute() ;
                $resultat1 = $rqt->fetch(\PDO::FETCH_ASSOC) ;
                
                if ( $resultat1['mois'] == $date ){
                    $session=$request->getSession();
                    $session->set('mois',$data['mois']);
                    $session->get('mois');
                
                    $session->set('annee',$data['annee']);
                    $session->get('annee');
                    
                    return $this->redirectToRoute( 'fiche', array( 'data' => $data ) ) ;
                }
                else {
                    return $this->redirectToRoute( 'connexion', array( 'data' => $data ) ) ;
                }
                
        }
        return $this->render( 'consulter/index.html.twig', array( 'choix' => $form->createView() ) ) ;
        
        
    }
}
