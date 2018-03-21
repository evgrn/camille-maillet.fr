<?php
namespace App\Controller\Front;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;



class HomeController extends Controller{

    public function indexAction(Request $request, \Swift_Mailer $mailer){


        $em = $this->getDoctrine()->getManager();

        $productionCategories = $em->getRepository('App:ProductionCategory')->findAll();
        $technologies = $em->getRepository('App:Technology')->findAll();
        $technologiesRepository = $em->getRepository('App:Technology');
        $masteredTechnologies = $technologiesRepository->getByMasteredAndPublished(true);
        $learnedTechnologies = $technologiesRepository->getByMasteredAndPublished(false);
        $bio = $em->getRepository('App:Bio')->getBio();
        $careerRepository = $em->getRepository('App:Career');
        $corporate = $careerRepository->findByCategory('Entreprise');
        $formation = $careerRepository->findByCategory('Formation');
        $irl = $em->getRepository('App:Irl')->findAll();

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $message->setDate(new \DateTime());
            $message->setProcessed(false);
            $em->persist($message);

            $response = new JsonResponse();
            $response->setData(array("success" => "Le message ayant pour objet <strong>\"{$message->getObject()}\"</strong> a été envoyé !"));
            $em->flush();

            $notification = (new \Swift_Message('Nouveau message sur Camille-Maillet.fr'))
                ->setFrom('maillet.camill@gmail.com')
                ->setTo('camille.maillet@protonmail.com')
                ->setBody(
                    $this->renderView(
                        'Email/message-notification.html.twig',
                        array('message' => $message)),
                    'text/html'
                );

            $mailer->send($notification);
            return $response;
        }




        return $this->render('Front/Home/index.html.twig', array(
            'technologies' => $technologies,
            'productionCategories' => $productionCategories,

            'masteredTechnologies' => $masteredTechnologies,
            'learnedTechnologies' =>$learnedTechnologies,
            'bio' => $bio,
            'corporateCareer' => $corporate,
            'formationCareer' => $formation,
            'irl' => $irl,
            'form' => $form->createView()
        ));
    }

    public function productionsAction($category){
        $productionRepository = $this->getDoctrine()->getManager()->getRepository('App:Production');
        $productions = ($category != 0) ? $productionRepository->findAllPublishedByCategory($category) : $productionRepository->findAllPublishedOrderedByDate();
        $productionsArray = array();
        foreach($productions as  $production){
            $productionsArray[] = $production->getPropertyArray();
        }
        $response = new JsonResponse();
        $response->setData(
            array(
                'imagesDirectory' => '/uploads/images',
                'productions' => $productionsArray
            ));


        return $response;
    }

    public function productionCategoriesAction(){
        $categoryRepository = $this->getDoctrine()->getManager()->getRepository('App:ProductionCategory');
        $categories = $categoryRepository->findAll();
        $categoriesArray = array();
        foreach($categories as  $category){
            $categoriesArray[] = $category->getPropertyArray();
        }
        $response = new JsonResponse();
        $response->setData($categoriesArray);
        return $response;
    }




}