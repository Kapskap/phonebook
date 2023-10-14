<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Contact;

class AddFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('company', TextType::class, [
                'label' => '* Firma: ',
            ])
            ->add('firstName', TextType::class, [
                'label' => ' Imię: ',
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'label' => ' Nazwisko: ',
                'required' => false,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Zatwierdź'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
