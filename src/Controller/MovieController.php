<?php

namespace App\Controller;

use App\Form\Movie\NewSubscriberType;
use App\Service\Brevo\Exception as BrevoException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Brevo\APIClient;

class MovieController extends AbstractController
{
    #[Route('/movie', name: 'movie_home')]
    public function home(Request $request, APIClient $client): Response
    {
        $session = $request->getSession();
        if (is_null($session->get('captcha-value'))) {
            $session->set('captcha-key', md5(time()));
            $session->set('captcha-value', rand(1000, 9999));
        }

        $defaultData = ['name' => null, 'email' => null, $session->get('captcha-key') => null];

        $form = $this->createForm(NewSubscriberType::class, $defaultData);
        $form->add($session->get('captcha-key'), HiddenType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // Checking against spam
            if ($form->get($session->get('captcha-key'))->getData() != $session->get('captcha-value')) {
                $form->addError(new FormError('Invalid form! Please try again to validate it.'));
                $session->set('captcha-value', rand(1000, 9999));

                $this->addFlash(
                    'error',
                    'Something went wrong on the form! Please, try again.'
                );
            }

            if ($form->isValid()) {
                try {
                    $client->newContact($form->getData(), APIClient::MOVIE_NEWSLETTER_SUBSCRIBER);
                } catch (BrevoException $e) {

                }

                $this->addFlash(
                    'success',
                    '<strong>Thank you for your trust!</strong> Watch your mailbox for fun and refreshing news. 😀'
                );

                return $this->redirectToRoute('movie_home');
            }
        }

        return $this->render('movie/home.html.twig', [
            'form' => $form,
        ]);
    }
}
