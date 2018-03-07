<?php

namespace App\Controller;

use App\Entity\TechnologyCategory;
use App\Form\TechnologyCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Technology;
use App\Entity\TechnologyCategoryStack;
use App\Form\TechnologyCategoryStackType;
use App\Form\TechnologyType;
use App\Form\TechnologyEditType;
use App\Service\ImageUploader;
use Symfony\Component\HttpFoundation\File\File;

class TechnologyController extends Controller
{
    /**
     * @Route("/technology/add", name="cm_back_technology_add")
     */
    public function addAction(Request $request, ImageUploader $imageUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $technology = new Technology();
        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $technology->getImage();
            $fileName = $imageUploader->upload($file);
            $technology->setImage($fileName);

            $em->persist($technology);
            $em->flush();

            return $this->redirectToRoute('cm_back_technology_single', array('id' => $technology->getId()));
        }

        return $this->render('Technology/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function categoryDeleteAction($id){
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository('App:TechnologyCategory')->find($id);
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('cm_back_technology_list');
    }

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
            return $this->redirectToRoute('cm_back_technology_list');
        }

        return $this->render('Technology/list.html.twig',
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

        return $this->render('Technology/short-list.html.twig', array('masteredTechnologies' => $masteredTechnologies, 'learnedTechnologies' => $learnedTechnologies));
    }

    public function singleAction(Request $request,  ImageUploader $imageUploader, $id){
        $em = $this->getDoctrine()->getManager();
        $technology = $em->getRepository('App:Technology')->find($id);
        $technologyPreview = clone $technology;
        $technology->setImage(
            new File($this->getParameter('images_directory').'/'.$technology->getImage())
        );
        $form = $this->createForm(TechnologyEditType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $technology->getImage();
            $fileName = $imageUploader->upload($file);
            $technology->setImage($fileName);

            $em->flush();

            return $this->redirectToRoute('cm_back_technology_single', array('id' => $technology->getId()));
        }

        return $this->render('Technology/single.html.twig', array('technology' => $technologyPreview, 'form' => $form->createView()));

    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $technology = $em->getRepository('App:Technology')->find($id);
            $em->remove($technology);
            $em->flush();


        return $this->redirectToRoute('cm_back_technology_list');
    }

    public function togglePublishedAction($id){
        $em = $this->getDoctrine()->getManager();
        $technology = $em->getRepository('App:Technology')->find($id);
        $newStatus = $technology->getPublished() ? false : true;
        $technology->setPublished($newStatus);
        $em->flush();

        return $this->redirectToRoute('cm_back_technology_list');

    }

    public function toggleMasteredAction($id){
        $em = $this->getDoctrine()->getManager();
        $technology = $em->getRepository('App:Technology')->find($id);
        $newStatus = $technology->getMastered() ? false : true;
        $technology->setMastered($newStatus);
        $em->flush();

        return $this->redirectToRoute('cm_back_technology_list');

    }

}