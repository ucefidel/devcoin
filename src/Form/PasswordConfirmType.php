<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PasswordConfirmType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordConfirmType::class, $this->getConfiguration('Ancien mot de passe', "Tapez l'ancien mot de passe", [
                'data' => ""
            ]))
            ->add('new_password', PasswordConfirmType::class, $this->getConfiguration('Nouveau mot de passe', "Tapez le nouveau mot de passe"))
            ->add('confirm_password', PasswordConfirmType::class, $this->getConfiguration('Confirmation du mot de passe', "Tapez la confirmation nouveau mot de passe"));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
