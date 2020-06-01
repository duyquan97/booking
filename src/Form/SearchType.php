<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('keyWord',TextType::class,[
                'required' => false,
                'attr' => [
                    'placeholder' => 'Search',
                ]
            ])
            ->add('fromPrice',TextType::class,[
                'required' => false,
            ])
            ->add('toPrice',TextType::class,[
                'required' => false,
            ])
            ->add('fromDate', DateType::class,[
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'required' => false,
                'label' => 'From Date',
                'widget' => 'single_text',
                'html5' => false,

            ])
            ->add('toDate',DateType::class,[
                'attr' => [
                    'class' => 'datetimepicker',
                ],
                'required' => false,
                'label' => 'To Date',
                'widget' => 'single_text',
                'html5' => false,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
