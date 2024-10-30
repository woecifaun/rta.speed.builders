<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Form\User\SignUpType;
use App\Service\User\Registrar;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/sign-up', name: 'user_register')]
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

            return $this->redirectToRoute('user_register_email_sent');
        }

        return $this->render('user/sign-up.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[Route('/user/pending-validation', name: 'user_register_email_sent')]
    public function registerEmailSent(): Response
    {
        return $this->render('user/pending-validation.html.twig');
    }

}
