<?php

namespace App\Controller\Back;

use App\Entity\TechnologyCategory;
use App\Form\TechnologyCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Technology;
use App\Form\TechnologyType;
use App\Form\TechnologyEditType;
use App\Service\ImageManager;
use Symfony\Component\HttpFoundation\File\File;

class TechnologyController extends Controller
{
    public function listAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $masteredTechnologies = $em->getRepository('App:Technology')->findByMastered(true);
        $learnedTechnologies = $em->getRepository('App:Technology')->findByMastered(false);
        $technologyCategories = $em->getRepository('App:TechnologyCategory')->findAll();


        $category = ($id) ? $em->getRepository('App:TechnologyCategory')->find($id) : new TechnologyCategory();
        $form = $this->createForm(TechnologyCategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if(!$id){
                $em->persist($category);
            }
            $em->flush();
            $notice= $id ? "La catégorie a été moifiée" : "La catégorie a été ajoutée";
            $this->get('session')->getFlashbag()->add('notice', $notice );
            return $this->redirectToRoute('cm_back_technology_list');
        }

        return $this->render('Back/Technology/list.html.twig',
            array(
                'masteredTechnologies' => $masteredTechnologies,
                'learnedTechnologies' => $learnedTechnologies,
                'technologyCategories' => $technologyCategories,
                'category' => $category,
                'form' => $form->createView()
            ));
    }

    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $masteredTechnologies = $em->getRepository('App:Technology')->findByMastered(true);
        $learnedTechnologies = $em->getRepository('App:Technology')->findByMastered(false);

        return $this->render('Back/Technology/short-list.html.twig', array('masteredTechnologies' => $masteredTechnologies, 'learnedTechnologies' => $learnedTechnologies));
    }

    public function addAction(Request $request, ImageManager $imageManager)
    {
        $em = $this->getDoctrine()->getManager();
        $technology = new Technology();
        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $technology->setImage(
                $imageManager->uploadImage($technology->getImage())
            );

            $em->persist($technology);
            $em->flush();
            $this->get('session')->getFlashbag()->add('notice', "La technologie a été ajoutée" );

            return $this->redirectToRoute('cm_back_technology_list');
        }

        return $this->render('Back/Technology/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function singleAction(Request $request, ImageManager $imageManager, $id){
        $em = $this->getDoctrine()->getManager();
        $technology = $em->getRepository('App:Technology')->find($id);
        $technologyPreview = clone $technology;

        $originalImage = $technology->getImage();
        $technology->setImage(new File($this->getParameter('images_directory').'/'. $originalImage));

        $form = $this->createForm(TechnologyEditType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $technology->setImage(
                $imageManager->manageImageUpdate($technology->getImage(), $originalImage)
            );

            $em->flush();
            $this->get('session')->getFlashbag()->add('notice', "La technologie a été modifiée" );

            return $this->redirectToRoute('cm_back_technology_list');
        }

        return $this->render('Back/Technology/single.html.twig', array('technology' => $technologyPreview, 'form' => $form->createView()));

    }

    public function deleteAction(Request $request, ImageManager $imageManager, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $technology = $em->getRepository('App:Technology')->find($id);
        $imageManager->delete($technology->getImage());
        $em->remove($technology);
        $em->flush();
        $this->get('session')->getFlashbag()->add('notice', "La technologie a été supprimée" );


        return $this->redirectToRoute('cm_back_technology_list');
    }

    public function togglePublishedAction($id){
        $em = $this->getDoctrine()->getManager();
        $technology = $em->getRepository('App:Technology')->find($id);
        $newStatus = $technology->getPublished() ? false : true;
        $technology->setPublished($newStatus);
        $em->flush();

        $notice= $technology->getPublished() ? "La technologiea été publiée" : "La technologie a été dépubliée";
        $this->get('session')->getFlashbag()->add('notice', $notice );

        return $this->redirectToRoute('cm_back_technology_list');

    }

    public function toggleMasteredAction($id){
        $em = $this->getDoctrine()->getManager();
        $technology = $em->getRepository('App:Technology')->find($id);
        $newStatus = $technology->getMastered() ? false : true;
        $technology->setMastered($newStatus);
        $em->flush();

        $notice= $technology->getMAstered() ? "La technologie a été marquée comme maîtrisée" : "La technologie a été marquée comme non-maîtrisée";
        $this->get('session')->getFlashbag()->add('notice', $notice );

        return $this->redirectToRoute('cm_back_technology_list');

    }

    public function categoryDeleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('App:TechnologyCategory')->find($id);
        $em->remove($category);
        $em->flush();

        $this->get('session')->getFlashbag()->add('notice', "La technologie a été supprimée" );

        return $this->redirectToRoute('cm_back_technology_list');
    }







}