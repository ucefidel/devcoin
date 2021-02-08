<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AnnonceType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration("Titre", "Votre titre"))
            ->add('description')
            ->add('picture', FileType::class, $this->getConfiguration(
                "Photo de l'annonce",
                "Photo de l'annonce",
                [
                    "mapped" => false,
                    "required" => true,
                    "constraints" =>
                        [
                            new File([
                                "mimeTypes" => ["image/jpeg"],
                                "mimeTypesMessage" => "Merci de mettre en pièce jointe l'image"
                            ])
                        ]
                ]
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
