<?php
namespace App\Controller\Front;

use App\Entity\Message;
use App\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * Class HomeController
 * @package App\Controller\Front
 *
 * Contrôleur de la partie Front-Office
 */
class HomeController extends Controller{

    /**
     * @param Request $request
     * @param \Swift_Mailer $mailer
     * @return JsonResponse|Response
     *
     * Affiche le front-office et gère la soumission du formulaire de contact ( envoi d'un mail est stockage en BDD)
     */
    public function indexAction(Request $request, \Swift_Mailer $mailer){

        $em = $this->getDoctrine()->getManager();

        // Récupération des entités

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


        // Gestion du formulaire

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $message->setDate(new \DateTime());
            // Le message est non-traité par défaut
            $message->setProcessed(false);



            // Envoi de la notification de message à l'adresse mail de l'administrateur
            $adminUserEmail = $em->getRepository('App:User')->find(0)->getEmail();
            $notification = (new \Swift_Message("CM Web - {$message->getAuthor()} : {$message->getObject()}"))
                ->setFrom($message->getEmail())
                ->setTo($adminUserEmail)
                ->setBody(
                    $this->renderView('Email/message-notification.html.twig', array('message' => $message)),
                    'text/html'
                )
            ;
            $mailer->send($notification);

            $em->persist($message);
            $em->flush();
            return new JsonResponse();
        }


        // Génération de la vue
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

    /**
     * @param $category
     * @return JsonResponse
     *
     * Renvoie les entités Production publiées et associées à la catégorie portanat l'id entré en paramètre sous forme de données JSON
     */
    public function productionsAction($category){

        $productionRepository = $this->getDoctrine()->getManager()->getRepository('App:Production');

        // Si l'id est 0, retourne toutes les réalisations sinon retourne la catégorie liée à l'id
        $productions = ($category != 0) ? $productionRepository->findAllPublishedByCategory($category) : $productionRepository->findAllPublishedOrderedByDate();

        // Génère un tableau stockant chaque entité sous forme de tableau
        $productionsArray = array();
        foreach($productions as  $production){
            $productionsArray[] = $production->getPropertyArray();
        }

        // Renvoie le tableau sous forme de donnés JSON
        $response = new JsonResponse();
        $response->setData(
            array(
                'imagesDirectory' => '/uploads/images',
                'productions' => $productionsArray
            ));
        return $response;
    }

    /**
     * @return JsonResponse
     *
     * Retourne la liste des catégories de réalisations sous forme de données JSON
     */
    public function productionCategoriesAction(){

        // Récupération des entités
        $categoryRepository = $this->getDoctrine()->getManager()->getRepository('App:ProductionCategory');
        $categories = $categoryRepository->findAll();

        // Génère un tableau stockant chaque entité sous forme de tableau
        $categoriesArray = array();
        foreach($categories as  $category){
            $categoriesArray[] = $category->getPropertyArray();
        }

        // Renvoie le tableau sous forme de donnés JSON
        $response = new JsonResponse();
        $response->setData($categoriesArray);
        return $response;
    }




}