<?php

namespace App\Controller;

use App\Entity\Furniture\Brand;
use App\Entity\Furniture\Model;
use App\Entity\Speedbuilding\Record;
use App\Form\Furniture\BrandSelectorType;
use App\Form\Furniture\ModelSelectorType;
use App\Form\Speedbuilding\NewRecordType;
use App\Form\Speedbuilding\TimeType;
use App\Repository\Furniture\ModelRepository;
use App\Repository\Speedbuilding\RecordRepository;
use App\Service\VideoIdParser\Exception as VidException;
use App\Service\VideoIdParser\VideoIdParser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Event\TerminateEvent;

class SubmitController extends AbstractController
{
    public function __construct(private EventDispatcherInterface $dispatcher)
    {
        // code...
    }
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
    public function submitSpeedbuilding(Request $request, Model $model, RecordRepository $repo): Response
    {
        $record = new Record();
        $record->timeToHisv(); // compute time in seconds to H:i:s.u for FormView

        $form = $this->createForm(NewRecordType::class, $record, ['model' => $model]);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            try {
                $video = (new VideoIdParser)->getPlatformAndId($record->getVideoUrl());
            } catch (VidException $e) {
                $form
                    ->get('videoUrl')
                    ->addError(new FormError('Video URL couldn\'t be analyzed! Please check your video URL.'));
            }

            if ($form->isValid()) {
                $record
                    ->setPostDate(new \DatetimeImmutable())
                    ->HisvToTime()
                    ->setVideoPlatform($video['platform'])
                    ->setVideoId($video['id'])
                ;

                if ($user = $this->getUser()) {
                    $record->setSpeedbuilder($user);
                }

                // Find rank of new record
                $rank = $repo->getRank($record);

                $record->setRank($rank)->setBestRank($rank);

                $repo->save($record, true);

                $this->dispatcher->addListener(KernelEvents::TERMINATE, function (TerminateEvent $event) use ($repo, $record) {
                    // Launch the job
                    $repo->propagateRank($record);
                });


                // once saved propagate ranks (Should probably an kernel.terminate event)

                return $this->redirectToRoute('browse_record', ['id' => $record->getId()]);
            }
        }

        return $this->render('submit/new-speedbuilding.html.twig', [
            'form' => $form,
            'model' => $model,
        ]);
    }
}
