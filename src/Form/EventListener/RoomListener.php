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

class RoomListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [

            FormEvents::SUBMIT => 'onSubmit',

        ];
    }

    public function onSubmit(FormEvent $event) {
        $slugify = new Slugify();
        $room = $event->getData();
        if ($room->getId() == null && !empty($room->getName())) {
            $room->setSlug(strtoupper(uniqid()).''.$slugify->slugify($room->getname()));
        }
    }

}