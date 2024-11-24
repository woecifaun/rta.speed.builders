<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Entity\User\ValidationToken as Token;
use App\Form\User\SignUpType;
use App\Form\User\ValidationLinkType;
use App\Service\Brevo\Exception as BrevoException;
use App\Service\User\Registrar;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


use App\Service\Brevo\Client;

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

        // First get rid off a simple page call (no form submission)
        if (!$form->isSubmitted()) {
            return $this->render('security/signup.html.twig', ['form' => $form]);
        }

        /* IF SUBMITTED FORM */

        // Check for existing user with same email address
        $address = $form->get('emailAddress')->getData();
        if ($existingUser = $registrar->getUserByEmailAddress($address)) {

            // Active user
            if ($existingUser->isActive()) {
                $this->addFlash('notice', 'An account with this email address already exists. Please login!');

                return $this->redirectToRoute('security_login');
            }

            // Pending validation user
            $this->addFlash('notice', 'Please, validate your email address by clicking the validation link sent in your inbox. If you need a new validation link, just fill in the form below');

            return $this->redirectToRoute('security_validation_link');
        }


        if ($form->isValid()) {
            try {
                $token = $registrar->register($user);
            } catch (BrevoException $e) {
                $this->addFlash(
                    'notice',
                    'Your account was succesfully created but something occured while sending the validation link. Please fill in the form in order to receive a new validation link. If the problem persists, please try later.')
                ;

                return $this->redirectToRoute('security_validation_link');
            }

            return $this->redirectToRoute('security_signup_pending');
        }

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
        #[MapEntity(mapping: ['token' => 'token'])] Token $token,
        Registrar $registrar
    ): Response {
        if ($token->isExpired()) {
            $this->addFlash('notice', 'The validation link is no longer valid! Please fill in the form for a new validation link.');

            return $this->render('security/validation-link.html.twig');
        }

        // when user is already active then send to login
        if ($token->getUser()->isActive()) {
            $this->addFlash('notice', 'Account already validated. Please login and enjoy the competition!');

            return $this->redirectToRoute('security_login');
        }

        $registrar->validateUser($token);

        $this->addFlash('notice', 'Your account has just been validated. Please login and enjoy the competition!');

        return $this->redirectToRoute('security_login');
    }

    #[Route('/signup/send-validation-link', name: 'security_validation_link')]
    public function sendValidationLink(
        Request $request,
        Registrar $registrar,
    ): Response {
        $user = new User();

        $form = $this->createForm(ValidationLinkType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $user = $registrar->getUserByEmailAddress($form->get('emailAddress')->getData());

            if (is_null($user)) {
                $this->addFlash('notice', 'Email address unknown! Please, sign up.');

                return $this->redirectToRoute('security_signup');
            }

            // when user is already active then send to login
            if ($user->isActive()) {
                $this->addFlash('notice', 'Account already validated. Please login and enjoy the competition!');

                return $this->redirectToRoute('security_login');
            }


            try {
                $token = $registrar->sendNewToken($user);
            } catch (BrevoException $e) {
                $form->addError(new FormError('Something occured while sending the validation link. Please fill in the form in order to receive a new validation link. If the problem persists, please try later.'));

                return $this->redirectToRoute('security_validation_link');
            }
        }

        // $form->clearError();

        return $this->render('security/validation-link.html.twig', [
            'form' => $form,
        ]);
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

    #[Route(path: '/login/forgotten-password', name: 'security_login_forgotten_password')]
    public function forgottenPassword(): Response
    {

        return $this->render('security/signup-pending.html.twig');
    }

    #[Route(path: '/logout', name: 'security_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
