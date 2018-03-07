<?php


namespace App\Controller\Back;

use App\Entity\ProductionCategory;
use App\Entity\Production;
use App\Form\ProductionCategoryType;
use App\Form\ProductionEditType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\ImageManager;
use App\Form\ProductionType;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


class ProductionController extends Controller
{

    public function listAction(Request $request, $id){
        $em = $this->getDoctrine()->getManager();
        $productions = $em->getRepository('App:Production')->findAllOrderedByDate();

        $categories = $em->getRepository('App:ProductionCategory')->findAll();

        $category = ($id) ? $em->getRepository('App:ProductionCategory')->find($id) : new ProductionCategory();

        if (!$category) {
            throw new NotFoundHttpException("La catégorie de réalisation n°$id n'existe pas.");
        }


        $form = $this->createForm(ProductionCategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if(!$id){
                $em->persist($category);
            }

            $em->flush();

            return $this->redirectToRoute('cm_back_production_list', array('_fragment' => 'categories'));
        }

        return $this->render('Back/Production/list.html.twig',
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

        return $this->render('Back/Production/short-list.html.twig', array("productions" => $productions));
    }

    public function shortCategoriesAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $productionCategories = $em->getRepository('App:ProductionCategory')->findAll();
        return $this->render('Back/Production/category-short-list.html.twig', array('productionCategories' => $productionCategories));
    }

    public function categoryDeleteAction(Request $request, ProductionCategory $category){
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        return $this->redirectToRoute('cm_back_production_categories');
    }

    public function addAction(Request $request, ImageManager $imageManager)
    {
        $em = $this->getDoctrine()->getManager();
        $production = new Production();
        $form = $this->createForm(ProductionType::class, $production);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $production->setImage(
                $imageManager->uploadImage($production->getImage())
            );

            $production->setThumbnail(
                $imageManager->uploadImage($production->getThumbnail())
            );

            $em->persist($production);
            $em->flush();

            return $this->redirectToRoute('cm_back_production_single', array('id' => $production->getId()));
        }

        return $this->render('Back/Production/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function singleAction(Request $request, ImageManager $imageManager, Production $production){
        $em = $this->getDoctrine()->getManager();
        $productionPreview = clone $production;

        $originalThumbnail = $production->getThumbnail();
        $originalImage = $production->getImage();
        $production->setThumbnail(
            new File($this->getParameter('images_directory').'/'.$originalThumbnail)
        );
        $production->setImage(
            new File($this->getParameter('images_directory').'/'.$originalImage)
        );
        $form = $this->createForm(ProductionEditType::class, $production);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                $production->setThumbnail(
                    $imageManager->manageImageUpdate($production->getThumbnail(), $originalThumbnail)
                );
                $production->setImage(
                    $imageManager->manageImageUpdate($production->getImage(), $originalImage)
                );

            $em->flush();

            return $this->redirectToRoute('cm_back_production_single', array('id' => $production->getId()));
        }

        return $this->render('Back/Production/single.html.twig', array('production' => $productionPreview, 'form' => $form->createView()));

    }

    public function deleteAction(Request $request, ImageManager $imageManager, Production $production)
    {
        $em = $this->getDoctrine()->getManager();

        $imageManager->delete($production->getImage());
        $imageManager->delete($production->getThumbnail());
        $em->remove($production);
        $em->flush();


        return $this->redirectToRoute('cm_back_production_list');
    }

    public function togglePublishedAction(Production $production){
        $em = $this->getDoctrine()->getManager();

        $newStatus = $production->getPublished() ? false : true;
        $production->setPublished($newStatus);
        $em->flush();

        return $this->redirectToRoute('cm_back_production_list');

    }

}