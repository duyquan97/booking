<?php
namespace App\Form\DataTransformer;
use App\Entity\User;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\TransactionRequiredException;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
class TestTransformer implements DataTransformerInterface
{
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function transform($user)
    {
        if (null === $user) {
            return '';
        }
        return $user->getEmail();
    }
    public function reverseTransform($email)
    {
        if (!$email) {
            return;
        }
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (null === $user) {
            $privateErrorMessage = sprintf('An user with number "%s" does not exist!', $email);
            $publicErrorMessage = 'The given "{{ value }}" value is not a valid issue number.';

            $failure = new TransformationFailedException($privateErrorMessage);
            $failure->setInvalidMessage($publicErrorMessage, [
                '{{ value }}' => $email,
            ]);

            throw $failure;
        }
        return $user;

    }
}