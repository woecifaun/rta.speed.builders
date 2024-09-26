<?php

namespace App\Controller;

use App\Entity\Assembly\Assembly;
use App\Entity\Assembly\Category;
use App\Entity\Furniture\Brand;
use App\Form\Assembly\NewType;
use App\Form\Assembly\CategoryType;
use App\Form\Furniture\BrandSelectorType;
use App\Form\Furniture\ModelSelectorType;
use App\Repository\Assembly\AssemblyRepository;
use App\Repository\Assembly\CategoryRepository;
use App\Repository\Furniture\BrandRepository;
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
            $repo->save($exhibition, true);

            $this->addFlash(
                'success',
                'Exhibition ' . $exhibition->getFullName() . ' successfully created.'
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

    #[Route('/manage/new-category/{slug:brand}/select-model', name: 'assembly_submit_select_model')]
    public function selectModel(Brand $brand, Request $request, BrandRepository $repo): Response
    {

        $brandForm = $this->createForm(BrandSelectorType::class, $brand);

        $category = new Category();
        $form = $this->createForm(ModelSelectorType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $repo->save($exhibition, true);

            // $this->addFlash(
            //     'success',
            //     'Exhibition ' . $exhibition->getFullName() . ' successfully created.'
            // );

            // return $this->redirectToRoute('home', ["id" => $assembly->getId()]);
        }

        return $this->render('assembly/category-select-model.html.twig', [
            'form' => $form,
            'brandForm' => $brandForm,
        ]);
    }


}
