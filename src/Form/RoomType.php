<?php

namespace App\Form;

use App\Entity\Room;
use App\Form\EventListener\RoomListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('shortDescription')
            ->add('description')
            ->add('province')
            ->add('district')
            ->add('street')
            ->add('type',ChoiceType::class,[
                'choices' => [
                    'CÙI' => 0,
                    'VIP' => 1,
                ]
            ])
            ->add('status',ChoiceType::class,[
                'choices' => [
                    'Off' => 0,
                    'On' => 1,
                ]
            ])
            ->add('featured',ChoiceType::class,[
                'choices' => [
                    'Off' => 0,
                    'On' => 1,
                ]
            ])
            ->addEventSubscriber(new RoomListener())
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
