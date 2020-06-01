<?php

namespace App\Form;

use App\Entity\DateRoom;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateRoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            'data_class' => DateRoom::class,
        ]);
    }
}
