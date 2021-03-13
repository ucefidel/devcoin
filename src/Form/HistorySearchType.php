<?php

namespace App\Form;

use App\Entity\HistorySearch;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class HistorySearchType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyword', TextType::class, $this->getConfiguration("Mot clés", "Mot clés à rechercher"))
            ->add('localization', TextType::class, $this->getConfiguration("Localisation", "Pays", [
                "required" => false
            ]));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => HistorySearch::class,
        ]);
    }
}
