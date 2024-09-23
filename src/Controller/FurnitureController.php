<?php

namespace App\Controller;

use App\Entity\Furniture\Brand;
use App\Form\Furniture\BrandType;
use App\Repository\Furniture\BrandRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FurnitureController extends AbstractController
{
    #[Route('/brand/add', name: 'furniture_brand_new')]
    public function addBrand(Request $request, BrandRepository $repo): Response
    {
        $brand = new Brand();

        $form = $this->createForm(BrandType::class, $brand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($brand, true);

            $this->addFlash(
                'success',
                'Brand ' . $brand->getName() . ' successfully created.'
            );

            return $this->redirectToRoute('home', ["id" => $brand->getId()]);
        }

        return $this->render('furniture/brand-new.html.twig', [
            'form' => $form,
            'brand' => $brand,
        ]);
    }


    #[Route('/brand/edit/{id}', name: 'furniture_brand_edit')]
    public function editBrand(Brand $brand, Request $request, BrandRepository $repo): Response
    {
        $form = $this->createForm(BrandType::class, $brand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($brand, true);

            $this->addFlash(
                'success',
                'Brand ' . $brand->getName() . ' successfully edited.'
            );

            return $this->redirectToRoute('home', ["id" => $brand->getId()]);
        }

        return $this->render('furniture/brand-new.html.twig', [
            'form' => $form,
            'brand' => $brand,
        ]);
    }
}
