<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

// src/Form/SearchFormType.php

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('query', TextType::class, [
                'label' => 'Podaj nazwę lub numer który szukasz: ',
                'required' => false,
            ]);
//            ->add('submit', SubmitType::class, [
//                'label' => 'Wyślij'
//            ]);
    }
}


