<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MiscController extends AbstractController
{
    #[Route('/other/faq', name: 'misc_faq')]
    public function homeTMP(): Response
    {
        return $this->render('misc/faq.html.twig');
    }
}
