<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Phone;

class AddPhoneFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('number', TextType::class, [
                'label' => '* nr telefonu ',
            ])
            ->add('typePhone', ChoiceType::class, [
                'label' => ' Telefon ',
                'choices' => [
                    'komórka' => 'komórka',
                    'domowy' => 'domowy',
                    'praca' => 'praca',
                ],
                'data' => 'komórka',
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zatwierdź'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Phone::class,
        ]);
    }
}
