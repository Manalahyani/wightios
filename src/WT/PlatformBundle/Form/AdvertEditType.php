<?php
// src/OC/PlatformBundle/Form/AdvertEditType.php
namespace WT\PlatformBundle\Form;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdvertEditType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    
  }
  public function getParent()
  {
    return AdvertType::class;
  }
}