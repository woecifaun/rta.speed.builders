<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Entity\User\ValidationToken as Token;
use App\Form\User\SignUpType;
use App\Service\User\Registrar;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class SecurityController extends AbstractController
{
    #[Route('/signup', name: 'security_signup')]
    public function signup(
        Request $request,
        Registrar $registrar
    ): Response {
        $user = new User();

        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $token = $registrar->register($user);

            // $this->addFlash('notice', 'Welcome! Your Account was succesfully created!');
            $this->addFlash('notice', $token);

            return $this->redirectToRoute('security_signup_pending');
        }

        return $this->render('security/signup.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Displayed when email for validating email address is just sent
     */
    #[Route('/signup/pending-validation', name: 'security_signup_pending')]
    public function signupPending(): Response
    {
        return $this->render('security/signup-pending.html.twig');
    }

    #[Route('/signup/validate/{token}', name: 'security_signup_validate')]
    public function vadidateEmailAddress(
        #[MapEntity(mapping: ['token' => 'token'])] ?Token $token,
        Registrar $registrar
    ): Response {
        if (!$token) {
            throw $this->createNotFoundException('Validation link not found! Please, check your email again.');
        }

        // when user is already active then send to login
        if ($token->getUser()->isActive()) {
            $this->addFlash('notice', 'Account already validated. Please login and enjoy the competition!');

            return $this->redirectToRoute('security_login');
        }

        if ($token->isExpired()) {
            return $this->render('security/signup-token-expired.html.twig');
        }

        $registrar->validateUser($token);

        $this->addFlash('notice', 'Your account has just been validated. Please login and enjoy the competition!');

        return $this->redirectToRoute('security_login');
    }

    #[Route(path: '/login', name: 'security_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'security_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/test-email', name: 'security_email_test')]
    public function testEmail(MailerInterface $mailer): Response
    {
        $params = [
            'subscriber' => 'Julien',
            'confirmationlink' => 'http://rta.speed.builders/register/validate/eanetn787eaniest8787'
        ];
        $email = (new Email())
            ->from('community@rta.speed.builders')
            ->to('julien@amazinglytch.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            // ->subject('Time for Symfony Mailer!')
            ->text('Sending emails is fun again!')
            ->html('<p>See Twig integration for better HTML integration!</p>');
        ;

        $email
            ->getHeaders()
            ->addTextHeader('templateId', 1)
            ->addParameterizedHeader('params', 'params', $params)
            // ->addTextHeader('foo', 'bar')
        ;

        $mailer->send($email);

        return new Response('<h1>Email Sent!</h1>');
    }
}
