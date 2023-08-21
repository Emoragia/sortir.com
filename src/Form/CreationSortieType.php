<?php

namespace App\Form;
use App\Entity\{Campus, Lieu, Sortie};

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
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

            ->add('nbInscriptionsMax',TextType::class,[
                'label'=>'Nombre de place : ',
                'required'=>true
            ])

            ->add('duree', ChoiceType::class,[
                'label'=>'Durée : ',
                'choices'=>[
                    30=>'30 min',
                    60=>'60 min',
                    90=>'90 min',
                    120=>'120 min',
                    150=>'150 min',
                    180=>'180 min'
                ],
                'multiple'=>false,
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

            ->add('participants',TextType::class,[
                'label'=> 'Participants : ',
                'required'=>true
            ])

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
            'campus' => Campus::class ,
        ]);
    }
}
