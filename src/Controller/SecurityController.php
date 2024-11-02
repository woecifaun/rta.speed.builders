<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route('/sign-up', name: 'security_register')]
    public function register(
        Request $request,
        Registrar $registrar
    ): Response {
        $user = new User();

        $form = $this->createForm(SignUpType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $registrar->register($user);

            $this->addFlash('notice', 'Acount created!');

            return $this->redirectToRoute('security_pending_validation');
        }

        return $this->render('security/sign-up.html.twig', [
            'form' => $form,
            // 'user' => $user,
        ]);
    }

    #[Route('/sign-up/pending-validation', name: 'security_pending_validation')]
    public function registerEmailSent(): Response
    {
        return $this->render('security/pending-validation.html.twig');
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
}
