<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MiscController extends AbstractController
{
    #[Route('/other/faq', name: 'misc_faq')]
    public function faq(): Response
    {
        return $this->render('misc/faq.html.twig');
    }

    #[Route('/other/glossary', name: 'misc_glossary')]
    public function glossary(): Response
    {
        return $this->render('misc/glossary.html.twig');
    }
}
