<?php
namespace App\Form\EventListener;

use App\Entity\Room;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Session\Session;
use Cocur\Slugify\Slugify;

class BookingListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [

            FormEvents::PRE_SET_DATA => 'onPreSetDate',
            FormEvents::SUBMIT => 'onSubmit',

        ];
    }

    public function onPreSetDate(FormEvent $event) {
        $form = $event->getForm();
        $booking = $event->getData();
        if ($booking->getId() != null) {
            $form->add('accept', ChoiceType::class,[
               'choices' => [
                   'Off' => 0,
                   'On' => 1,
               ]
            ]);
        }
    }
    public function onSubmit(FormEvent $event) {
        $booking = $event->getData();
        if ($booking->getId() == null ) {
            $code = strtoupper(uniqid());
            $booking->setCode($code);
        }
    }

}