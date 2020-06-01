<?php
namespace App\Form\DataTransformer;
use App\Entity\User;
use App\Repository\Room2Repository;
use App\Repository\RoomsRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\HttpFoundation\Session\Session;

class UserTransformer implements DataTransformerInterface
{
    private $room2Repository;
    public function __construct(Room2Repository $room2Repository)
    {
        $this->room2Repository = $room2Repository;
    }
    public function transform($room)
    {
        if (null === $room) {
            return '';
        }
        return $room->getId();
    }
    public function reverseTransform($roomId)
    {
        if (!$roomId) {
            return;
        }
        $user = $this->room2Repository->find($roomId);
        if (null === $user) {
            $privateErrorMessage = sprintf('An room with number "%s" does not exist!', $roomId);
            $publicErrorMessage = 'The given "{{ value }}" value is not a valid room number.';

            $failure = new TransformationFailedException($privateErrorMessage);
            $failure->setInvalidMessage($publicErrorMessage, [
                '{{ value }}' => $roomId,
            ]);

            throw $failure;
        }
        return $user;

    }
}