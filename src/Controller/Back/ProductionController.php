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
use App\Service\PublicationToggler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class ProductionController
 * @package App\Controller\Back
 *
 * Contrôleur de la partie "Réalisations" du back-office
 */
class ProductionController extends Controller
{

    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affiche les liste des réalisations et des catégories de réalisations, ainsi qu'un formulaire d'édition/ajout d'une catégorie.
     */
    public function listAction(Request $request, $id){

        // Récupération des entités
        $em = $this->getDoctrine()->getManager();
        $productions = $em->getRepository('App:Production')->findAllOrderedByDate();
        $categories = $em->getRepository('App:ProductionCategory')->findAll();

        // Gestion du formulaire
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
            $notice = $id ? "La catégorie a été mise à jour" : "La catégorie a été ajoutée";
            $this->get('session')->getFlashbag()->add('notice', $notice );


            return $this->redirectToRoute('cm_back_production_list', array('_fragment' => 'categories'));
        }

        // Génération de la vue
        return $this->render('Back/Production/list.html.twig',
            array(
                "productions" => $productions,
                "categories" => $categories,
                "category" => $category,
                "form" => $form->createView()
            ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affichage de la liste des réalisations dans le dashboard.
     */
    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $productions = $em->getRepository('App:Production')->findAllOrderedByDate();

        return $this->render('Back/Production/short-list.html.twig', array("productions" => $productions));
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affichage de la liste des catégories de réalisation dans le dashboard
     */
    public function shortCategoriesAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $productionCategories = $em->getRepository('App:ProductionCategory')->findAll();
        return $this->render('Back/Production/category-short-list.html.twig', array('productionCategories' => $productionCategories));
    }

    /**
     * @param Request $request
     * @param ProductionCategory $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Suppression de l'entité ProductionCategory entrée en paramètre
     */
    public function categoryDeleteAction(Request $request, ProductionCategory $category){
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();
        $this->get('session')->getFlashbag()->add('notice', "La catégorie a été supprimée" );

        return $this->redirectToRoute('cm_back_production_list');
    }

    /**
     * @param Request $request
     * @param ImageManager $imageManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affiche un formulaire d'ajout d'une entité Production
     */
    public function addAction(Request $request, ImageManager $imageManager)
    {
        $em = $this->getDoctrine()->getManager();
        $production = new Production();
        $form = $this->createForm(ProductionType::class, $production);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {



            $production->setImage(
                // Stockage de l'image
                $imageManager->uploadImage($production->getImage())
            );

            $production->setThumbnail(
                // Stockage du thumbnail
                $imageManager->uploadImage($production->getThumbnail())
            );

            $production->setPreview(
                // Stockage de la preview
                $imageManager->uploadImage($production->getPreview())
            );

            $em->persist($production);
            $em->flush();
            $this->get('session')->getFlashbag()->add('notice', "Le projet a été modifié" );

            return $this->redirectToRoute('cm_back_production_list');
        }

        return $this->render('Back/Production/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @param Request $request
     * @param ImageManager $imageManager
     * @param Production $production
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affiche un aperçu de l'entité Production entrée en paramètre ainsi qu'un formulaire d'édition.
     */
    public function singleAction(Request $request, ImageManager $imageManager, Production $production){

        // Récupération de l'entité
        $em = $this->getDoctrine()->getManager();
        $productionPreview = clone $production;

        // Afin de ne pas avoir de problème avec les champ image, preview et thumbnail du formulaire, on clone l'objet $production.
        // Son clone $productionPreview servira à hydrater la partie aperçu
        // L'objet $production initial, qui servira à hydrater le formulaire, se voit remplacer ses propriétés $image, $thumbnail et $preview par des objets de type File attendus par le formulaire.
        $originalThumbnail = $production->getThumbnail();
        $originalImage = $production->getImage();
        $originalPreview = $production->getPreview();

        $production->setThumbnail(
            new File($this->getParameter('images_directory').'/'.$originalThumbnail)
        );
        $production->setImage(
            new File($this->getParameter('images_directory').'/'.$originalImage)
        );
        $production->setPreview(
            new File($this->getParameter('images_directory').'/'.$originalPreview)
        );

        // Gestion du formulaire
        $form = $this->createForm(ProductionEditType::class, $production);
        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {

                // Mise à jour des images

                $production->setThumbnail(
                    $imageManager->manageImageUpdate($production->getThumbnail(), $originalThumbnail)
                );
                $production->setImage(
                    $imageManager->manageImageUpdate($production->getImage(), $originalImage)
                );
                $production->setPreview(
                    $imageManager->manageImageUpdate($production->getPreview(), $originalPreview)
                );

            $em->flush();
                $this->get('session')->getFlashbag()->add('notice', "Le projet à été ajouté" );

            return $this->redirectToRoute('cm_back_production_single', array('id' => $production->getId()));
        }

        // Génération de la vue
        return $this->render('Back/Production/single.html.twig', array('production' => $productionPreview, 'form' => $form->createView()));

    }

    /**
     * @param Request $request
     * @param ImageManager $imageManager
     * @param Production $production
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Suppression de l'entité $production entrée en paramètre.
     */
    public function deleteAction(Request $request, ImageManager $imageManager, Production $production)
    {
        $em = $this->getDoctrine()->getManager();

        $imageManager->delete($production->getImage());
        $imageManager->delete($production->getThumbnail());
        $imageManager->delete($production->getPreview());

        $em->remove($production);
        $em->flush();
        $this->get('session')->getFlashbag()->add('notice', "Le projet à été supprimé" );


        return $this->redirectToRoute('cm_back_production_list');
    }

    /**
     * @param Production $production
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Change le statut de l'élément ( publié / non-publié)
     */
    public function togglePublishedAction(Production $production, PublicationToggler $toggler){
        $this->get('session')->getFlashbag()
             ->add('notice', $toggler->toggle($production) );
        return $this->redirectToRoute('cm_back_production_list');

    }

}