<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Entity\User\ValidationToken as Token;
use App\Repository\User\UserRepository;
use App\Repository\User\ValidationTokenRepository as TokenRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class Registrar
{
    public function __construct(
        private UserRepository $uRepo,
        private UserPasswordHasherInterface $hasher,
        private TokenRepository $tRepo,
        private $validationTokenExpiry
    ) {}

    public function register(User $user)
    {
        $hashed = $this->hasher->hashPassword($user, $user->getPassword());

        $user->setRegisteredOn(new \DateTimeImmutable())
            ->setPassword($hashed)
            ->setStatus(User::STATUS_PENDING)
        ;

        $expiry = (new \DateTimeImmutable())->modify($this->validationTokenExpiry);
        $token = new Token($user, $expiry);

        $this->tRepo->save($token);

        // flush is made for both token and user thanks to the 'true' argument below
        $this->uRepo->save($user, true);

        // sending link to user with Brevo

        return $token->getToken();
    }

    public function validateUser(Token $token)
    {
        $token->getUser()->setStatus(User::STATUS_ACTIVE);

        $token->setValidatedAt(new \DateTimeImmutable());

        $this->tRepo->save($token);

        // flush is made for both token and user thanks to the 'true' argument below
        $this->uRepo->save($token->getUser(), true);
    }
}
