<?php

namespace App\Controller;

use App\Entity\Furniture\Brand;
use App\Entity\Furniture\Model;
use App\Entity\Speedbuilding\Category;
use App\Entity\Speedbuilding\Record;
use App\Entity\User\User;
use App\Repository\Speedbuilding\RecordRepository;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
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

    #[Route('/record/{id}', name: 'browse_record')]
    public function browseRecord(Record $record): Response
    {
        return $this->render('browse/record.html.twig',[
            'record' => $record,
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

    #[Route('/speedbuilder/{username}', name: 'browse_speedbuilder')]
    public function browseSpeedbuilder(#[MapEntity(mapping: ['username' => 'username'])] User $user): Response
    {
        // $records = $this->repo->findBestRecordsForCategory($category);

        return $this->render('browse/speedbuilder.html.twig',[
            'speedbuilder' => $user,
        ]);
    }

    #[Route('/model/{id}', name: 'browse_model')]
    public function browseModel(Model $model): Response
    {
        dump($model);die;
        // $records = $this->repo->findBestRecordsForCategory($category);

        return $this->render('browse/home.html.twig',[
            'records' => $records,
        ]);
    }

    #[Route('/brand/{id}', name: 'browse_brand')]
    public function browseBrand(Brand $brand): Response
    {
        dump($brand);die;
        // $records = $this->repo->findBestRecordsForCategory($category);

        return $this->render('browse/home.html.twig',[
            'records' => $records,
        ]);
    }
}
