<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use \PDO;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\VersionStrategy\EmptyVersionStrategy;

class SaisirController extends AbstractController
{
    
    public function index(Request $test)
    {
        
        $session = $test->getSession() ;
        $idV = $session->get( 'id' ) ;
        $prenom = $session->get( 'prenom' ) ;
        $nom = $session->get( 'nom' ) ;
        
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
                        ->add( 'SeDéconnecter' , SubmitType::class )
			->getForm() ;
        
        $form->handleRequest( $request ) ;
        
        if ( $form->isSubmitted() && $form->isValid() ) {
            return $this->redirectToRoute( 'saisir' , array( 'formulaire' => $form->createView() ) ) ;
        }
        
        return $this->render('saisir/index.html.twig', [
            'controller_name' => 'SaisirController',
            'formulaire' => $form->createView() ,
            'idVisiteur' => $idV ,
            'prenomV' => $prenom ,
            'nomV' => $nom ,
            'todaymy' => $todaymy ,
        ]);
    }

}