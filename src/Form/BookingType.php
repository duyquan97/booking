<?php

namespace App\Form;

use App\Entity\Booking;
use App\Form\DataTransformer\RoomTransformer;
use App\Form\EventListener\BookingListener;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BookingType extends AbstractType
{
    private $roomTransformer;
    public function __construct(RoomTransformer $roomTransformer)
    {
        $this->roomTransformer = $roomTransformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price',null,[
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
            ->add('roomCount', NumberType::class, [
            ])
            ->add('person', NumberType::class)
            ->add(
                $builder->create('room',TextType::class,[
                    'invalid_message' => 'That is not a valid room number',])
                    ->addModelTransformer($this->roomTransformer))
            ->addEventSubscriber(new BookingListener())
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
