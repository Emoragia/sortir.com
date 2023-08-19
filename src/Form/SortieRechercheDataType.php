<?php

namespace App\Form;

use App\Data\SortieRechercheData;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
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
//            ->add('participant', HiddenType::class, [
////                'data'=> $this->security->getUser()
//            ])
//            ->add('campus')
            ->add('nomRecherche', TextType::class, [
                'label'=> 'Rechercher'
            ])
//            ->add('borneDateInf')
//            ->add('borneDateSup')
//            ->add('organisateur')
//            ->add('inscrit')
//            ->add('nonInscrit')
//            ->add('sortiesPassees')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SortieRechercheData::class,
        ]);
    }
}
