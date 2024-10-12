<?php

namespace App\Controller;

use App\Form\Speedbuilding\TimeType;
use App\Service\Speedbuilding\Time;

use App\Entity\Assembly\Assembly;
use App\Entity\Furniture\Brand;
use App\Entity\Furniture\Model;
use App\Form\Speedbuilding\NewRecordType;
use App\Form\Furniture\BrandSelectorType;
use App\Form\Furniture\ModelSelectorType;
use App\Repository\Assembly\AssemblyRepository;
use App\Repository\Furniture\ModelRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubmitController extends AbstractController
{
    #[Route('/submit/select-brand', name: 'submit_select_brand')]
    public function submitSelectBrand(Request $request): Response
    {
        $form = $this->createForm(BrandSelectorType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form->get('brand')->getData()->getId();

            return $this->redirectToRoute('submit_select_model', ["id" => $id]);
        }

        return $this->render('submit/select-brand.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/submit/brand-{id:brand}/select-model', name: 'submit_select_model')]
    public function submitSelectModel(Request $request, Brand $brand): Response
    {
        $form = $this->createForm(ModelSelectorType::class, null, ['brand' => $brand]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $id = $form->get('model')->getData()->getId();

            return $this->redirectToRoute('submit_speedbuilding', ["id" => $id]);
        }

        return $this->render('submit/select-model.html.twig', [
            'form' => $form,
            'brand' => $brand,
        ]);
    }

    #[Route('/submit/model-{id}', name: 'submit_speedbuilding')]
    public function submitSpeedbuilding(Request $request, Model $model, AssemblyRepository $repo): Response
    {
        $assembly = new Assembly();
        $assembly->timeToHisv();

        $form = $this->createForm(NewRecordType::class, $assembly, ['model' => $model]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $now = new \DatetimeImmutable();
            $assembly->setPostDate($now)->HisvToTime();

            $repo->save($assembly, true);

            return $this->redirectToRoute('home');
        }

        return $this->render('submit/new-speedbuilding.html.twig', [
            'form' => $form,
            'model' => $model,
        ]);
    }
}
