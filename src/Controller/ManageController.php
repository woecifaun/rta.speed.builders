<?php

namespace App\Controller;

use App\Entity\Speedbuilding\Category;
use App\Entity\Furniture\Brand;
use App\Form\Speedbuilding\CategoryEditType;
use App\Form\Speedbuilding\CategoryNewType;
use App\Form\Furniture\BrandSelectorType;
use App\Repository\Speedbuilding\CategoryRepository;
use App\Repository\Furniture\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ManageController extends AbstractController
{
    #[Route('/manage/new-category/select-brand', name: 'manage_category_select_brand')]
    public function manageSelectBrand(Request $request): Response
    {
        $form = $this->createForm(BrandSelectorType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form->get('brand')->getData()->getId();

            return $this->redirectToRoute('manage_category_new', ["id" => $id]);
        }

        return $this->render('manage/category-select-brand.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/manage/new-category/brand-{id:brand}', name: 'manage_category_new')]
    public function manageNewCategory(Brand $brand, Request $request, CategoryRepository $repo, ModelRepository $mRepo): Response
    {
        $category = new Category();

        if ($modelId = $request->get('model')) {
            if ($model = $mRepo->findOneById($modelId)) {
                $category->setModel($model);
            }
        }

        $form = $this->createForm(CategoryNewType::class, $category, ['brand' => $brand]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($category, true);

            $this->addFlash(
                'success',
                'Category  ' . $category->getName() . ' successfully created.'
            );

            return $this->redirectToRoute('manage_category_edit', ["id" => $category->getId()]);
        }

        return $this->render('manage/category-new.html.twig', [
            'form' => $form,
            'brand' => $brand
        ]);
    }

    #[Route('/manage/category/{id}/edit', name: 'manage_category_edit')]
    public function manageEditCategory(Category $category, Request $request, CategoryRepository $repo): Response
    {
        $form = $this->createForm(CategoryEditType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($category, true);

            $this->addFlash(
                'success',
                'Category  ' . $category->getName() . ' successfully updated.'
            );

            return $this->redirectToRoute('manage_category_preview', ["id" => $category->getId()]);
        }

        return $this->render('manage/category-edit.html.twig', [
            'form' => $form,
            'category' => $category,
        ]);
    }

    #[Route('/manage/category/{id}/preview', name: 'manage_category_preview')]
    public function managePreviewCategory(Category $category): Response
    {
        return $this->render('manage/category-preview.html.twig', [
            'category' => $category
        ]);
    }
}
