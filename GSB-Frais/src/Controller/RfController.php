<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

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
				->add( 'suivant' , SubmitType::class )
				->add( 'annuler' , SubmitType::class )
	                        ->add( 'valider' , SubmitType::class )
				->getForm() ;
	                
			$form->handleRequest( $request ) ;
	 
			//if ( $form->isSubmitted() && $form->isValid() ) {
	                #if ( $form->getClickedButton() === $form->get('suivant') ) {
				$data = $form->getData() ;
	                        array( 'data' => $data ) ;
                                return $this->render( 'rf/index.html.twig', array( 'formu' => $form->createView() ) ) ;
}
}