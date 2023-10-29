<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\Image;
use App\Entity\Contact;

class AddContactFormType extends AbstractType
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
            ->add('photo', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image(['maxSize' => '1024k'])
                ],
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
