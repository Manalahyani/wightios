<?php

namespace WT\PlatformBundle\Controller;
use WT\PlatformBundle\Entity\Lettre;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;



class LettreController extends Controller
{
  public function indexAction($page)
  {
    if ($page < 1) {
      throw new NotFoundHttpException('Page "'.$page.'" inexistante.');
    }
    // Notre liste d'annonce en dur
    $listAdverts = array(
      array(
        'nom'   => 'Recherche développpeur Symfony',
        'id'      => 1,
        'mail'  => 'Alexandre',
        'num' => 'Nous rkjk…',
        'date'    => new \Datetime()),
      array(
        'nom'   => 'Mission de webmaster',
        'id'      => 2,
        'mail'  => 'Hugo',
        'num' => 'Nous rrnet. Blabla…',
        'date'    => new \Datetime()),
      array(
        'nom'   => 'Offre de stage webdesigner',
        'id'      => 3,
        'mail'  => 'Mathieu',
        'num' => 'Nous propos',
        'date'    => new \Datetime())
    );
    return $this->render('WTPlatformBundle:Advert:index.html.twig', array(
      'listAdverts' => $listAdverts,
    ));
  }
  public function vAction()
  {
    // On récupère le repository
    $repository = $this->getDoctrine()
      ->getManager()
      ->getRepository('WTPlatformBundle:Lettre')
    ;

    // On récupère l'entité correspondante à l'id $id
    $lettre = $repository->findAll();

    
    // Le render ne change pas, on passait avant un tableau, maintenant un objet
    return $this->render('WTPlatformBundle:Lettre:v.html.twig', array(
      'lettre' => $lettre
    ));
  }
 
  /**public function ajouAction(Request $request)
  {
    // On crée un objet Advert
    $lettre = new Lettre();

    // J'ai raccourci cette partie, car c'est plus rapide à écrire !
    $form = $this->get('form.factory')->createBuilder(FormType::class, $lettre)

      ->add('titre',    TextType::class) 
      ->add('age',     NumberType::class)
      ->add('contenu',   TextType::class)
      ->add('save',      SubmitType::class) 
      ->add('reset',      ResetType::class)
      ->getForm()
    ;

    // Si la requête est en POST
    if ($request->isMethod('POST')) {
      // On fait le lien Requête <-> Formulaire
      // À partir de maintenant, la variable $advert contient les valeurs entrées dans le formulaire par le visiteur
      $form->handleRequest($request);

      // On vérifie que les valeurs entrées sont correctes
      // (Nous verrons la validation des objets en détail dans le prochain chapitre)
      if ($form->isValid()) {
        // On enregistre notre objet $advert dans la base de données, par exemple
        $em = $this->getDoctrine()->getManager();
        $em->persist($lettre);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'bien enregistrée.');

        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('wt_platform_ajou');
      }*/
    

  


  
}