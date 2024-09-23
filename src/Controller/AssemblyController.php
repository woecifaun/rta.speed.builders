<?php

namespace App\Controller;

use App\Entity\Assembly;
use App\Form\Assembly\NewType;
use App\Repository\AssemblyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AssemblyController extends AbstractController
{
    #[Route('/submit', name: 'assembly_submit')]
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

        return $this->render('submit.html.twig', [
            'form' => $form,
            'assembly' => $assembly,
        ]);
    }
}
