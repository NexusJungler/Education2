<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class eleveType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 'text')
            ->add('prenom', 'text', ['label' => 'Prénom'])
            ->add('photo', 'file')
            ->add('age', 'birthday', [
                'years' => range(1990, 2010),
                'format' => 'ddMMyyyy'])
            ->add('voie', 'choice', ['choices'=>[
                'rue' => 'rue',
                'route' => 'route',
                'chemin' => 'chemin',
                'avenue' => 'avenue',
                'boulevard' =>'boulevard',
                'place' => 'place']  ])
            ->add('numero', 'integer')
            ->add('complement', 'text', [
                'invalid_message' => 'You entered an invalid value, it should include %num% letters',
                'invalid_message_parameters' => array('%num%' => 6)])
            ->add('codepost', 'integer', ['label' => 'Code postal'])
            ->add('ville', 'text')
            ->add('classe', 'entity', [
                    'class' => 'AppBundle:classe',
                    'choice_label' => 'nom']);
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\eleve'
        ));
    }
}
