<?php

namespace App\Form;

use App\Entity\DateRoom;
use App\Entity\PriceRoom;
use App\Entity\Room;
use App\Entity\SetRoom;
use App\Repository\DateRoomRepository;
use App\Repository\PriceRoomRepository;
use App\Repository\SetRoomRepository;
use Carbon\Carbon;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SetRoomType extends AbstractType
{
    private $dateRoomRepository;
    private $now;
    private $priceRoomRepository;
    public function __construct(DateRoomRepository $dateRoomRepository, PriceRoomRepository $priceRoomRepository)
    {
        $this->dateRoomRepository = $dateRoomRepository;
        $this->priceRoomRepository = $priceRoomRepository;
        $this->now = date_create(date('Y-m-d',strtotime("now")));
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roomCount')
            ->add('person')
            ->add('discount')
            ->add('room',EntityType::class, [
                'class' => Room::class,
                'choice_label' => 'name'
            ])
            ->add('priceRoom', EntityType::class, [
                'class' => PriceRoom::class,
                'choice_label' => function(PriceRoom $priceRoom) {
                    return sprintf(' %s %s', number_format($priceRoom->getPrice()), $priceRoom->getCurrency());
                },
                'choices' => $this->priceRoomRepository->findOrDerByPrice(),
            ])
            ->add('dateRoom', EntityType::class, [
                'class' => DateRoom::class,
                'choice_label' => function(DateRoom $dateRoom) {
                    $formDate = Carbon::parse($dateRoom->getFromDate())->toDateString();
                    $toDate = Carbon::parse($dateRoom->getToDate())->toDateString();
                    return sprintf(' %s to %s', $formDate, $toDate);
                },
                'choices' => $this->dateRoomRepository->findOrDerByFromDate($this->now),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SetRoom::class,
        ]);
    }
}
