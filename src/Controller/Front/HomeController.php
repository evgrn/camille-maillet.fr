<?php
namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class HomeController extends Controller{

    public function indexAction(Request $request){

        $em = $this->getDoctrine()->getManager();

        $productionCategories = $em->getRepository('App:ProductionCategory')->findAll();
        $technologies = $em->getRepository('App:Technology')->findAll();
        $productions = $em->getRepository('App:Production')->findAllPublishedOrderedByDate();
        $technologiesRepository = $em->getRepository('App:Technology');
        $masteredTechnologies = $technologiesRepository->findByMastered(true);
        $learnedTechnologies = $technologiesRepository->findByMastered(false);
        $bio = $em->getRepository('App:Bio')->getBio();
        $careerRepository = $em->getRepository('App:Career');
        $corporate = $careerRepository->findByCategory('Entreprise');
        $formation = $careerRepository->findByCategory('Formation');
        $irl = $em->getRepository('App:Irl')->findAll();



        return $this->render('Front/Home/index.html.twig', array(
            'technologies' => $technologies,
            'productionCategories' => $productionCategories,
            'productions' => $productions,
            'masteredTechnologies' => $masteredTechnologies,
            'learnedTechnologies' =>$learnedTechnologies,
            'bio' => $bio,
            'corporateCareer' => $corporate,
            'formationCareer' => $formation,
            'irl' => $irl
        ));
    }

}