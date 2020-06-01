<?php

namespace App\Form;

use App\Entity\Room;
use App\Form\EventListener\RoomListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RoomType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Name not blank'
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Come on, you can think of a name longer than that!'
                    ])
                ]
            ])
            ->add('shortDescription')
            ->add('description')
            ->add('province', ChoiceType::class,[
                'choices' => [
                    'Ha Noi' => 'Ha Noi',
                    'Ho CHi Minh' => 'Ho CHi Minh',
                    'Da Nang' => 'Da Nang',
                    'Quang Ninh' => 'Quang Ninh',
                ]
            ])
            ->add('district', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'District not blank'
                    ]),
                ]
            ])
            ->add('street', TextType::class,[
                'constraints' => [
                    new NotBlank([
                        'message' => 'Street not blank'
                    ]),
                ]
            ])
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
