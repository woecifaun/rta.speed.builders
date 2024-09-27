<?php

namespace App\Controller;

use App\Entity\Assembly\Assembly;
use App\Entity\Assembly\Category;
use App\Entity\Furniture\Brand;
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

class AssemblyController extends AbstractController
{
    #[Route('/submit', name: 'assembly_submit_select_brand')]
    public function submit(Request $request, AssemblyRepository $repo): Response
    {
        $assembly = new Assembly();

        $form = $this->createForm(NewType::class, $assembly);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $repo->save($assembly, true);

            $this->addFlash(
                'success',
                'Assembly ' . $assembly->getName() . ' successfully created.'
            );

            return $this->redirectToRoute('home', ["id" => $assembly->getId()]);
        }

        return $this->render('assembly/submit.html.twig', [
            'form' => $form,
            'assembly' => $assembly,
        ]);
    }

    #[Route('/manage/new-category/select-brand', name: 'manage_category_select_brand')]
    public function selectBrand(Request $request, BrandRepository $repo): Response
    {
        $form = $this->createForm(BrandSelectorType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $form->get('slug')->getData()->getSlug();

            return $this->redirectToRoute('assembly_submit_select_model', ["slug" => $slug]);
        }

        return $this->render('assembly/category-select-brand.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/manage/new-category/{slug:brand}', name: 'manage_category_new')]
    public function newCategory(Brand $brand, Request $request, CategoryRepository $repo): Response
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
    public function editCategory(Category $category, Request $request, CategoryRepository $repo): Response
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
