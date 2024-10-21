<?php

namespace App\Controller;

use App\Entity\Speedbuilding\Category;
use App\Repository\Speedbuilding\RecordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BrowseController extends AbstractController
{
    public function __construct(private RecordRepository $repo) {}

    #[Route('/', name: 'home_tmp')]
    public function homeTMP(): Response
    {
        return $this->render('splash.html.twig');
    }

    #[Route('/home', name: 'home')]
    public function home(): Response
    {
        $records = $this->repo->findRecordsPostedMostRecently();

        return $this->render('browse/home.html.twig',[
            'records' => $records,
        ]);
    }

    #[Route('/category/{id}', name: 'browse_category')]
    public function browseCategory(Category $category): Response
    {
        dump($category);die;
        // $records = $this->repo->findBestRecordsForCategory($category);

        return $this->render('browse/home.html.twig',[
            'records' => $records,
        ]);
    }
}
