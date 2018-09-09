<?php
// src/OC/PlatformBundle/Controller/AdvertController.php
namespace WT\PlatformBundle\Controller;
use WT\PlatformBundle\Entity\Advert;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
//use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WT\PlatformBundle\Form\AdvertType;
use WT\PlatformBundle\Form\AdvertEditType;


class AdvertController extends Controller
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
  public function viewAction()
  {
    // On récupère le repository
    $repository = $this->getDoctrine()
      ->getManager()
      ->getRepository('WTPlatformBundle:Advert')
    ;

    // On récupère l'entité correspondante à l'id $id
    $advert = $repository->findAll();

    
    // Le render ne change pas, on passait avant un tableau, maintenant un objet
    return $this->render('WTPlatformBundle:Advert:view.html.twig', array(
      'advert' => $advert
    ));
  }
  public function addAction(Request $request)
  {
    // On crée un objet Advert
    $advert = new Advert();

    // J'ai raccourci cette partie, car c'est plus rapide à écrire !
    $form = $this->get('form.factory')->createBuilder(FormType::class, $advert)

      ->add('date',    DateType::class) 
      ->add('nom',     TextType::class)
      ->add('mail',   EmailType::class)
      ->add('num',    TextType::class)
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
        $em->persist($advert);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice', 'bien enregistrée.');

        // On redirige vers la page de visualisation de l'annonce nouvellement créée
        return $this->redirectToRoute('wt_platform_add');
      }
    }

    // À ce stade, le formulaire n'est pas valide car :
    // - Soit la requête est de type GET, donc le visiteur vient d'arriver sur la page et veut voir le formulaire
    // - Soit la requête est de type POST, mais le formulaire contient des valeurs invalides, donc on l'affiche de nouveau
    return $this->render('WTPlatformBundle:Advert:add.html.twig', array(
      'form' => $form->createView(),
    ));
  }
  

  public function editAction($id, Request $request)
  {
    $em = $this->getDoctrine()->getManager();

    $advert = $em->getRepository('WTPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L''id ".$id." n'existe pas.");
    }

    $form = $this->get('form.factory')->create(AdvertEditType::class, $advert);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      // Inutile de persister ici, Doctrine connait déjà notre annonce
      $em->flush();

      $request->getSession()->getFlashBag()->add('notice', ' bien modifiée.');

      return $this->redirectToRoute('wt_platform_view', array('id' => $advert->getId()));
    }

    return $this->render('WTPlatformBundle:Advert:edit.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView(),
    ));
  }

  public function deleteAction(Request $request, $id)
  {
    $em = $this->getDoctrine()->getManager();

    $advert = $em->getRepository('WTPlatformBundle:Advert')->find($id);

    if (null === $advert) {
      throw new NotFoundHttpException("L'id ".$id." n'existe pas.");
    }

    // On crée un formulaire vide, qui ne contiendra que le champ CSRF
    // Cela permet de protéger la suppression d'annonce contre cette faille
    $form = $this->get('form.factory')->create();

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em->remove($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('info', " bien supprimée.");

      return $this->redirectToRoute('wt_platform_view');
    }
    
    return $this->render('WTPlatformBundle:Advert:delete.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView(),
    ));
  }
  public function menuAction($limit)
  {
    // On fixe en dur une liste ici, bien entendu par la suite on la récupérera depuis la BDD !
    $listAdverts = array(
      array('id' => 2, 'nom' => 'Recherche développeur Symfony'),
      array('id' => 5, 'nom' => 'Mission de webmaster'),
      array('id' => 9, 'nom' => 'Offre de stage webdesigner')
    );
    return $this->render('WTPlatformBundle:Advert:menu.html.twig', array(
      // Tout l'intérêt est ici : le contrôleur passe les variables nécessaires au template !
      'listAdverts' => $listAdverts
    ));
  }
   public function triAction()
  {
    $em = $this->getDoctrine()->getManager();

    // temporarily abuse this controller to see if this all works
    $advert = $em->getRepository('WTPlatformBundle:Advert');
        var_dump($advert->findByDate(array('advert'=>'advert')));
return $this->render('WTPlatformBundle:Advert:date.html.twig', array(
      'advert' => $advert
    ));

  }


  
}