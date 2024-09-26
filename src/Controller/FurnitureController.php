<?php

namespace App\Controller;

use App\Entity\Furniture\Brand;
use App\Entity\Furniture\Model;
use App\Form\Furniture\BrandType;
use App\Form\Furniture\ModelType;
use App\Repository\Furniture\BrandRepository;
use App\Repository\Furniture\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FurnitureController extends AbstractController
{
    #[Route('/manage/add-brand', name: 'manage_brand_add')]
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

            return $this->redirectToRoute('manage_brand_edit', ["id" => $brand->getId()]);
        }

        return $this->render('furniture/brand.html.twig', [
            'form' => $form,
            'brand' => $brand,
        ]);
    }


    #[Route('/manage/brand/edit/{id}', name: 'manage_brand_edit')]
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

        return $this->render('furniture/brand.html.twig', [
            'form' => $form,
            'brand' => $brand,
        ]);
    }

    #[Route('/manage/add-model', name: 'manage_model_add')]
    public function addModel(Request $request, ModelRepository $repo): Response
    {
        $model = new Model();

        $form = $this->createForm(ModelType::class, $model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($model, true);

            $this->addFlash(
                'success',
                'Model ' . $model->getName() . ' successfully created.'
            );

            return $this->redirectToRoute('manage_model_edit', ["id" => $model->getId()]);
        }

        return $this->render('furniture/model.html.twig', [
            'form' => $form,
            'model' => $model,
        ]);
    }

    #[Route('/manage/model/edit/{id}', name: 'manage_model_edit')]
    public function editModel(Model $model, Request $request, ModelRepository $repo): Response
    {
        $form = $this->createForm(ModelType::class, $model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($model, true);

            $this->addFlash(
                'success',
                'Model ' . $model->getName() . ' successfully updated.'
            );

            return $this->redirectToRoute('manage_model_edit', ["id" => $model->getId()]);
        }

        return $this->render('furniture/model.html.twig', [
            'form' => $form,
            'model' => $model,
        ]);
    }
}
