<?php

namespace App\Controller;

use App\Entity\Assembly\Assembly;
use App\Entity\Assembly\Category;
use App\Entity\Furniture\Brand;
use App\Entity\Furniture\Model;
use App\Form\Assembly\CategoryType;
use App\Form\Assembly\NewType;
use App\Form\Furniture\BrandSelectorType;
use App\Form\Furniture\ModelSelectorType;
use App\Repository\Assembly\AssemblyRepository;
use App\Repository\Assembly\CategoryRepository;
use App\Repository\Furniture\BrandRepository;
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
            $slug = $form->get('brand')->getData()->getSlug();

            return $this->redirectToRoute('manage_category_new', ["slug" => $slug]);
        }

        return $this->render('assembly/category-select-brand.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/manage/new-category/{slug:brand}', name: 'manage_category_new')]
    public function manageNewCategory(Brand $brand, Request $request, CategoryRepository $repo): Response
    {
        $category = new Category();
        $category->tmpBrand = $brand; // >_< UGLY but working for now

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($category, true);

            $this->addFlash(
                'success',
                'Category  ' . $category->getName() . ' successfully created.'
            );

            return $this->redirectToRoute('manage_category_edit', ["id" => $category->getId()]);
        }

        return $this->render('assembly/category-new.html.twig', [
            'form' => $form,
            'brand' => $brand
        ]);
    }

    #[Route('/manage/category/{id}', name: 'manage_category_edit')]
    public function manageEditCategory(Category $category, Request $request, CategoryRepository $repo): Response
    {
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($category, true);

            $this->addFlash(
                'success',
                'Category  ' . $category->getName() . ' successfully updated.'
            );

            return $this->redirectToRoute('manage_category_edit', ["id" => $category->getId()]);
        }

        return $this->render('assembly/category-edit.html.twig', [
            'form' => $form,
            'brand' => $category->getModel()->getBrand(),
        ]);
    }
}
