<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Registrar
{
    public function __construct(private UserRepository $repo, private UserPasswordHasherInterface $hasher) {}

    public function register(User $user)
    {
        $hashed = $this->hasher->hashPassword($user, $user->getPassword());

        $user->setRegisteredOn(new \DateTimeImmutable())
            ->setPassword($hashed)
            ->setStatus(User::STATUS_PENDING)
        ;

        $this->repo->save($user, true);

        // activation link in DB

        // sending link to user with Brevo
    }
}
