<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Phones;

class AddFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Company', TextType::class, [
                'label' => 'Firma: ',
            ])
            ->add('Firstname', TextType::class, [
                'label' => 'Imię: ',
                'required' => false,
            ])
            ->add('Lastname', TextType::class, [
                'label' => 'Nazwisko: ',
                'required' => false,
            ])
            ->add('Number', TextType::class, [
                'label' => 'Nr telefonu: '
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Wyślij'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Phones::class,
        ]);
    }
}

?>