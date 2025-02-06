<?php

namespace App\Controller;

use App\Form\Movie\NewSubscriberType;
use App\Service\Brevo\Exception as BrevoException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Brevo\APIClient;

class MovieController extends AbstractController
{
    #[Route('/movie', name: 'movie_home')]
    public function home(Request $request, APIClient $client): Response
    {
        $defaultData = ['name' => null, 'email' => null];
        $form = $this->createForm(NewSubscriberType::class, $defaultData);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // $client->newContact($form->getData(), APIClient::MOVIE_NEWSLETTER_SUBSCRIBER);
            } catch (BrevoException $e) {

            }

            $this->addFlash(
                'success',
                '<strong>Thank you for your trust!</strong> Watch your mailbox for fun and refreshing news. 😀'
            );

            return $this->redirectToRoute('movie_home');
        }

        return $this->render('movie/home.html.twig', [
            'form' => $form,
        ]);
    }
}
