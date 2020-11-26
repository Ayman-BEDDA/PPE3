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
    
    public function index(Request $request)
    {
        
        $form = $this->createFormBuilder(  )
                
            ->add( 'mois' , IntegerType::class )    
            ->add( 'annee' , IntegerType::class )    
            
            ->add( 'repas' , TextType::class )
            ->add( 'nuitees' , TextType::class )
            ->add( 'etapes' , TextType::class )
            ->add( 'km' , TextType::class )
                
            
            
            ->add( 'envoyer' , SubmitType::class )
            ->add( 'effacer' , ResetType::class )
            ->getForm() ;
            
        $form->handleRequest( $request ) ;
        
        if ( $form->isSubmitted() && $form->isValid() ) {
            $data = $form->getData() ;
           
                array( 'data' => $data ) ;
                
                $pdo = new \PDO('mysql:host=localhost; dbname=GSB_FRAIS', 'developpeur', 'azerty');
                
                $rqt = $pdo->prepare("UPDATE INTO `FraisForfait` (`id`, `libelle`, `montant`) VALUES(:repas,:nuitees,:etapes,:km)") ;
                $rqt->bindParam(':repas', $data['repas']);
                $rqt->bindParam(':nuitees', $data['nuitees']);
                $rqt->bindParam(':etapes', $data['etapes']);
                $rqt->bindParam(':km', $data['km']);
                $rqt->execute() ;
                $resultat1 = $rqt->fetch(\PDO::FETCH_ASSOC) ;
                
                
        }
        return $this->render( 'saisir/index.html.twig', array( 'saisir' => $form->createView() ) ) ;
    }
}
