<?php

namespace App\Form;

use App\Entity\Apprenant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApprenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('telephone')
            ->add('email')
            ->add('dateNaissance')
            ->add('lieuNaissance')
            ->add('sexe')
            ->add('niveauEtude')
            ->add('attentes')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Apprenant::class,
        ]);
    }
}
