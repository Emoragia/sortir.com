<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnulerSortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=> 'Nom de la sortie :',
                'disabled'=>true,
            ])
            ->add('dateHeureDebut', DateType::class, [
                'label'=>'Date de la sortie :',
                'disabled' => true,
                'widget'=>'single_text'
            ])
            ->add('siteOrganisateur', TextType::class, [
                'label'=> 'Campus :',
                'disabled'=>true,
            ])
            ->add('lieu', TextType::class, [
                'label'=> 'Lieu :',
                'disabled'=>true,
            ])
            ->add('infosSortie', TextareaType::class, [
                'label'=> 'Motif :',
                'data'=>'',
                'required'=>true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
