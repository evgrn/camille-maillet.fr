<?php


namespace App\Controller;

use App\Entity\ProductionCategory;
use App\Entity\Production;
use App\Form\ProductionCategoryType;
use App\Form\ProductionEditType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\ImageUploader;
use App\Form\ProductionType;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ProductionController extends Controller
{

    public function categoryDeleteAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $productionCategory = $em->getRepository('App:ProductionCategory')->find($id);
        $em->remove($productionCategory);
        $em->flush();

        return $this->redirectToRoute('cm_back_production_categories');
    }

    public function shortCategoriesAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $productionCategories = $em->getRepository('App:ProductionCategory')->findAll();
        return $this->render('Production/category-short-list.html.twig', array('productionCategories' => $productionCategories));
    }

    public function addAction(Request $request, ImageUploader $imageUploader)
    {
        $em = $this->getDoctrine()->getManager();
        $production = new Production();
        $form = $this->createForm(ProductionType::class, $production);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $production->getThumbnail();
            $fileName = $imageUploader->upload($file);
            $production->setThumbnail($fileName);

            $file = $production->getImage();
            $fileName = $imageUploader->upload($file);
            $production->setImage($fileName);

            $em->persist($production);
            $em->flush();

            return $this->redirectToRoute('cm_back_production_single', array('id' => $production->getId()));
        }

        return $this->render('Production/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function listAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $productions = $em->getRepository('App:Production')->findAllOrderedByDate();

        $categories = $em->getRepository('App:ProductionCategory')->findAll();

        $category = ($id) ? $em->getRepository('App:ProductionCategory')->find($id) : new ProductionCategory();


        $form = $this->createForm(ProductionCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!$id){
                $em->persist($category);
            }

            $em->flush();

            return $this->redirectToRoute('cm_back_production_list', array('_fragment' => 'categories'));
        }

        return $this->render('Production/list.html.twig',
            array(
                "productions" => $productions,
                "categories" => $categories,
                "category" => $category,
                "form" => $form->createView()
            ));
    }

    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $productions = $em->getRepository('App:Production')->findAllOrderedByDate();

        return $this->render('Production/short-list.html.twig', array("productions" => $productions));
    }

    public function singleAction(Request $request,  ImageUploader $imageUploader, $id){
        $em = $this->getDoctrine()->getManager();
        $production = $em->getRepository('App:Production')->find($id);
        $productionPreview = clone $production;
        $production->setThumbnail(
            new File($this->getParameter('images_directory').'/'.$production->getThumbnail())
        );
        $production->setImage(
            new File($this->getParameter('images_directory').'/'.$production->getImage())
        );
        $form = $this->createForm(ProductionEditType::class, $production);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $production->getThumbnail();
            $fileName = $imageUploader->upload($file);
            $production->setThumbnail($fileName);

            $file = $production->getImage();
            $fileName = $imageUploader->upload($file);
            $production->setImage($fileName);

            $em->flush();

            return $this->redirectToRoute('cm_back_production_single', array('id' => $production->getId()));
        }

        return $this->render('Production/single.html.twig', array('production' => $productionPreview, 'form' => $form->createView()));

    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $production = $em->getRepository('App:Production')->find($id);
        $em->remove($production);
        $em->flush();


        return $this->redirectToRoute('cm_back_production_list');
    }

    public function togglePublishedAction($id){
        $em = $this->getDoctrine()->getManager();
        $production = $em->getRepository('App:Production')->find($id);
        $newStatus = $production->getPublished() ? false : true;
        $production->setPublished($newStatus);
        $em->flush();

        return $this->redirectToRoute('cm_back_production_list');

    }

}