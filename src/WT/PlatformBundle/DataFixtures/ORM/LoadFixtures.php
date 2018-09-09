<?php
// src/OC/PlatformBundle/DataFixtures/ORM/LoadCategory.php

namespace WT\PlatformBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\PlatformBundle\Entity\Advert;

class LoadCategory implements FixtureInterface
{
  // Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
  public function load(ObjectManager $manager)
  {
    // Liste des noms de catégorie à ajouter
    $names = array(
      'Développement web',
      'Développement@mobile',
      '12345',
      new date_time()
    );

    foreach ($names as $name) {
      // On crée la catégorie
      $advert = new Advert();
      $advert->setNom($name);

      // On la persiste
      $manager->persist($advert);
    }

    // On déclenche l'enregistrement de toutes les catégories
    $manager->flush();
  }
}