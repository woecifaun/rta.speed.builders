<?php

namespace App\Service\User;

use App\Entity\User\User;
use App\Entity\User\ValidationToken as Token;
use App\Repository\User\UserRepository;
use App\Repository\User\ValidationTokenRepository as TokenRepository;
use App\Service\Brevo\Client as BrevoClient;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Registrar
{
    public function __construct(
        private UserRepository $uRepo,
        private UserPasswordHasherInterface $hasher,
        private TokenRepository $tRepo,
        private $validationTokenExpiry,
        private BrevoClient $brevo,
        private UrlGeneratorInterface $router,
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

        // flush is made for both with the 'true' argument in the second save call
        $this->tRepo->save($token);
        $this->uRepo->save($user, true);

        // sending link to user with Brevo
        $link = $this->router->generate(
            'security_signup_validate',
            ['token' => $token->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $this->brevo->sendSignupValidationLink($user->getEmailAddress(), $user->getUsername(), $link);
    }

    public function validateUser(Token $token)
    {
        $token->getUser()->setStatus(User::STATUS_ACTIVE);

        $token
            ->setStatus(Token::STATUS_ACTIVATOR)
            ->setValidatedAt(new \DateTimeImmutable());

        // flush is made for both with the 'true' argument in the second save call
        $this->tRepo->save($token);
        $this->uRepo->save($token->getUser(), true);
    }

    public function getUserByEmailAddress(string $emailAddress): ?User
    {
        return $this->uRepo->getUserByEmailAddress($emailAddress);
    }

    public function sendNewToken(User $user)
    {
        $this->tRepo->depreciateUserTokens($user);

        $expiry = (new \DateTimeImmutable())->modify($this->validationTokenExpiry);
        $token = new Token($user, $expiry);

        $this->tRepo->save($token, true);

        // sending link to user with Brevo
        $link = $this->router->generate(
            'security_signup_validate',
            ['token' => $token->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $this->brevo->sendSignupValidationLink($user->getEmailAddress(), $user->getUsername(), $link);
    }
}
