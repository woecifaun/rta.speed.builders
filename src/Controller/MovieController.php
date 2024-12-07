<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MovieController extends AbstractController
{
    #[Route('/movie', name: 'movie_home')]
    public function home(): Response
    {
        $records = $this->repo->findRecordsPostedMostRecently();

        return $this->render('browse/home.html.twig',[
            'records' => $records,
        ]);
    }
}
