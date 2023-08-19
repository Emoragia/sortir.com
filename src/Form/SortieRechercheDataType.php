<?php

namespace App\Form;

use App\Data\SortieRechercheData;
use App\Entity\Campus;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieRechercheDataType extends AbstractType
{
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('campus', EntityType::class, [
                'class'=> Campus::class,
                'choice_label'=> 'nom',
                'label'=> 'Campus :',
                'placeholder'=> '--Tous les campus--',
                'row_attr' => [
                    'class' => 'input-group',
                ],
                'required' => false

            ])
            ->add('nomRecherche', TextType::class, [
                'label'=> 'Rechercher',
                'attr'=>[
                    'placeholder'=>'Le nom contient...'
                ],
                'row_attr' => [
                    'class' => 'input-group',
                ],
                'required'=>false,
            ])
            ->add('borneDateInf', DateType::class, [
                'label'=>'Entre le ',
                'widget'=>'single_text',
//                'time_widget'=>'single_text',
                'row_attr' => [
                    'class' => 'input-group',
                ],
                'required'=>false
            ])
            ->add('borneDateSup', DateType::class, [
                'label'=>'et le',
                'widget'=>'single_text',
                'row_attr' => [
                    'class' => 'input-group',
                ],
//                'time_widget'=>'single_text',
                'required'=>false
            ])
            ->add('organisateur', CheckboxType::class, [
                'label'=> 'Sorties dont je suis l\'organisateur.ice',
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'required'=>false

            ])
            ->add('inscrit', CheckboxType::class, [
                'label'=> 'Sorties auxquelles je suis inscrit.e.',
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'required'=>false

            ])
            ->add('nonInscrit',CheckboxType::class, [
                'label'=> 'Sorties auxquelles je ne suis pas inscrit.e.',
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'required'=>false

            ])
            ->add('sortiesPassees', CheckboxType::class, [
                'label'=> 'Sorties passées.',
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'required'=>false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortieRechercheData::class,
        ]);
    }
}
