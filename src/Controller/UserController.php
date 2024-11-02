<?php

namespace App\Controller;

use App\Entity\User\User;
use App\Form\User\DisplayNameType;
use App\Repository\User\UserRepository;
use App\Service\User\Registrar;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException As DuplicateException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class UserController extends AbstractController
{
    public function __construct(private UserRepository $repo) {}

    #[Route('/my/profile', name: 'user_profile')]
    public function userProfile(): Response
    {
        return $this->render('user/profile.html.twig');
    }

    #[Route('/my/settings', name: 'user_settings')]
    public function settingList(): Response
    {
        return $this->render('user/settings.html.twig');
    }

    #[Route('/my/display-name', name: 'user_display_name')]
    public function displayName(#[CurrentUser] ?User $user, Request $request): Response
    {
        $form = $this->createForm(DisplayNameType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $this->repo->save($user, true);

                $this->addFlash('notice', 'Display name updated');

                return $this->redirectToRoute('user_settings');
            } catch (DuplicateException $e) {
                $form->get('displayName')->addError(new FormError('Display name already in use. Please select another one'));

            }
        }

        return $this->render('user/display-name.html.twig', [
            'form' => $form,
        ]);
    }
}
