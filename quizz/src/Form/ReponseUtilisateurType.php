<?php

namespace App\Form;

use App\Entity\ReponseUtilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReponseUtilisateurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Reponse', EntityType::class,[
                   'class' => 'App\Entity\Reponse',
                   'expanded'=> true,
                   'multiple'=>false
                ])

            // ->add('Reponse', CheckboxType::class)

            ->add('Utilisateur')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ReponseUtilisateur::class,
        ]);
    }
}
