<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ImportType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('import', FileType::class, $this->getConfiguration("Fichier", "Fichier à importer", [
                "mapped" => false,
                "required" => true,
                "constraints" =>
                    [
                        new File([
                            "mimeTypesMessage" => "Merci de mettre en pièce jointe le fichier CSV"
                        ])
                    ]
            ]));
    }

}
