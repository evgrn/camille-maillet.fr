<?php

namespace App\Controller\Back;

use App\Entity\TechnologyCategory;
use App\Form\TechnologyCategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Technology;
use App\Form\TechnologyType;
use App\Form\TechnologyEditType;
use App\Service\ImageManager;
use Symfony\Component\HttpFoundation\File\File;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Service\PublicationToggler;

/**
 * Class TechnologyController
 * @package App\Controller\Back
 *
 * Contrôleur de la partie "Technologies" du back-office
 */
class TechnologyController extends Controller
{
    /**
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affiche la liste des technologies et un formulaire d'édition / ajout.
     */
    public function listAction(Request $request, $id){

        // Récupération des entités
        $em = $this->getDoctrine()->getManager();
        $masteredTechnologies = $em->getRepository('App:Technology')->findByMastered(true);
        $learnedTechnologies = $em->getRepository('App:Technology')->findByMastered(false);
        $technologyCategories = $em->getRepository('App:TechnologyCategory')->findAll();

        // Gestion du formulaire
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

        // Génération de la vue
        return $this->render('Back/Technology/list.html.twig',
            array(
                'masteredTechnologies' => $masteredTechnologies,
                'learnedTechnologies' => $learnedTechnologies,
                'technologyCategories' => $technologyCategories,
                'category' => $category,
                'form' => $form->createView()
            ));
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * Affichage de la liste des technologies dans le dashboard.
     */
    public function shortListAction(){
        $em = $this->getDoctrine()->getManager();
        $masteredTechnologies = $em->getRepository('App:Technology')->findByMastered(true);
        $learnedTechnologies = $em->getRepository('App:Technology')->findByMastered(false);

        return $this->render('Back/Technology/short-list.html.twig', array('masteredTechnologies' => $masteredTechnologies, 'learnedTechnologies' => $learnedTechnologies));
    }

    /**
     * @param Request $request
     * @param ImageManager $imageManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affichage d'un formulaire d'ajout de technologie.
     */
    public function addAction(Request $request, ImageManager $imageManager){
        $em = $this->getDoctrine()->getManager();
        $technology = new Technology();
        $form = $this->createForm(TechnologyType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Upload de l'image
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

    /**
     * @param Request $request
     * @param ImageManager $imageManager
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * Affiche l'aperçu et un formulaire d'édition de l'entité Technology ayant pour ID la valeur $id entrée en paramètre.
     */
    public function singleAction(Request $request, ImageManager $imageManager, Technology $technology){

        $em = $this->getDoctrine()->getManager();


        // Afin de ne pas avoir de problème avec le champ image, on clone l'objet $technology.
        // Son clone $technologyPreview servira à hydrater la partie aperçu
        // L'objet $technology initial, qui servira à hydrater le formulaire, se voit remplacer sa propriétés $image par un objet de type File attendu par le formulaire.

        $technologyPreview = clone $technology;
        $originalImage = $technology->getImage();
        $technology->setImage(new File($this->getParameter('images_directory').'/'. $originalImage));

        $form = $this->createForm(TechnologyEditType::class, $technology);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $technology->setImage(
                $imageManager->manageImageUpdate($technology->getImage(), $originalImage)
            );

            $em->flush();
            $this->get('session')->getFlashbag()->add('notice', "La technologie a été modifiée" );

            return $this->redirectToRoute('cm_back_technology_list');
        }

        return $this->render('Back/Technology/single.html.twig', array('technology' => $technologyPreview, 'form' => $form->createView()));

    }

    /**
     * @param Request $request
     * @param ImageManager $imageManager
     * @param Technology $technology
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Suppression de l'entité $technology entrée en paramètre.
     */
    public function deleteAction(Request $request, ImageManager $imageManager, Technology $technology){
        $em = $this->getDoctrine()->getManager();

        // Suppression de l'image affiliée
        $imageManager->delete($technology->getImage());

        $em->remove($technology);
        $em->flush();
        $this->get('session')->getFlashbag()->add('notice', "La technologie a été supprimée" );

        return $this->redirectToRoute('cm_back_technology_list');
    }

    /**
     * @param PublicationToggler $toggler
     * @param Technology $technology
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Suppression de l'entité $production entrée en paramètre.
     */
    public function togglePublishedAction(PublicationToggler $toggler, Technology $technology){
        $this->get('session')->getFlashbag()
            ->add('notice', $toggler->toggle($technology) );
        return $this->redirectToRoute('cm_back_technology_list');
    }

    /**
     * @param Technology $technology
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Change le statut de l'élément $technology entré en paramètre( maîtrisé / non-maîtrisé)
     */
    public function toggleMasteredAction(Technology $technology){

        // Récupération de l'élément
        $em = $this->getDoctrine()->getManager();
        $technology = $em->getRepository('App:Technology')->find($id);

        // Modification du statut
        $newStatus = $technology->getMastered() ? false : true;
        $technology->setMastered($newStatus);

        // Sauvegarde de l'élément
        $em->flush();

        // Affichage de la notification
        $notice= $technology->getMastered() ? "La technologie a été marquée comme maîtrisée" : "La technologie a été marquée comme non-maîtrisée";
        $this->get('session')->getFlashbag()->add('notice', $notice );

        // Redirection vers la la liste des technologies
        return $this->redirectToRoute('cm_back_technology_list');

    }

    /**
     * @param TechnologyCategory $category
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     *
     * Supprime l'entité $technologyCategory entrée en paramètre
     */
    public function categoryDeleteAction(TechnologyCategory $category){
        $em = $this->getDoctrine()->getManager();
        $em->remove($category);
        $em->flush();

        $this->get('session')->getFlashbag()->add('notice', "La technologie a été supprimée" );

        return $this->redirectToRoute('cm_back_technology_list');
    }







}