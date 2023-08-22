<?php

namespace App\Form;
use App\Entity\{Campus, Lieu, Sortie};

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreationSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom',TextType::class, array(
                'label'=>'Nom de la sortie : ',
                'required'=>true
            ))
            ->add('dateHeureDebut',
                DateTimeType::class,
                [
                    'label' => 'Date et heure de la sortie :',
                    'html5' => true,
                    'widget' => 'single_text',
                    'required' => true
                ])

            ->add('dateLimiteInscription',DateType::class,[
                'label'=>'Date limite de l\'inscription',
                'html5'=>true,
                'widget'=>'single_text',
                'required'=>true
            ])

            ->add('nbInscriptionsMax', NumberType::class,[
                'label'=>'Nombre de place : ',
                'data'=> 4,
                'html5'=> true,
                'required'=>true
            ])

            ->add('duree', NumberType::class,[
                'label'=>'Durée : ',
                'data'=> 30,
                'html5'=> true,
                'required'=>true
            ])

            ->add('infosSortie',TextareaType::class,[
                'label'=> 'Information sur la sortie : ',
                'required'=>true
            ])

            ->add('siteOrganisateur',TextType::class,[
                'label'=> 'Campus : ',
                'disabled'=>true
            ])

        //   ->add('participants',TextType::class,[
        //       'label'=> 'Participants : ',
        //      'required'=>true
        //    ])

            ->add('lieu', EntityType::class,[
                'label'=> 'Lieu : ',
                'class'=> Lieu::class,
                'choice_label'=> 'nom',
                'placeholder'=>'tous les lieux',
                'required'=>true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'campus' => Campus::class
        ]);
    }
}