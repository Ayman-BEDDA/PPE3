<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class ConsulterController extends AbstractController
{

    public function index()
    {
        $request = Request::createFromGlobals() ;
                
        $form = $this->createFormBuilder(  )
            ->add( 'mois' , ChoiceType::class,[
    'choices' => [
        'janvier' => 1,
        'fevrier' => 2,
        'mars' => 3,
        'avril' => 4,
        'mai'=>5,
        'juin' => 6,
        'juillet' => 7,
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
            return $this->render('consulter/index.html.twig',array('data'=>$data));
        }
        return $this->render( 'consulter/index.html.twig', array( 'choix' => $form->createView() ) ) ;
        
        
    }
}
