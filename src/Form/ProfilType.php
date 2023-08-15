<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Participant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label'=> 'Pseudo : ',
                'required'=> true,
            ])
            ->add('prenom', TextType::class, [
                'label'=>'Prénom : ',
                'required'=>true,
            ])
            ->add('nom', TextType::class, [
                'label'=>'Nom : ',
                'required'=>true,
            ])
            ->add('telephone', TextType::class, [
                'label'=>'Téléphone : ',
                'required'=>false,
            ])
            ->add('email', EmailType::class, [
                'label'=>"Email : ",
                'required'=>true,
            ])
            ->add('motPasseClair', RepeatedType::class, [
                'type'=> PasswordType::class,
                'trim'=>true,
                'invalid_message' => 'Les mots de passe saisis ne correspondent pas.',
                'required'=>false,
                'first_options' => ['label' => 'Nouveau mot de passe'],
                'second_options' => ['label' => 'Confirmation du mot de passe'],
            ])
            ->add('campus', TextType::class, [
                'label'=> 'Campus',
                'disabled'=>true
            ])
//            ->add('photo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
