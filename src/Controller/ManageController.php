<?php

namespace App\Controller;

use App\Entity\Furniture\Brand;
use App\Entity\Speedbuilding\Category;
use App\Entity\Speedbuilding\CategoryVersion as Version;
use App\Entity\User\User;
use App\Form\Furniture\BrandSelectorType;
use App\Form\Speedbuilding\CategoryNewType;
use App\Form\Speedbuilding\CategoryVersionEditType as VersionType;
use App\Repository\Furniture\ModelRepository;
use App\Repository\Speedbuilding\CategoryRepository;
use App\Service\Category\VersionManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;


#[IsGranted('ROLE_USER')]
class ManageController extends AbstractController
{
    #[Route('/manage/new-category/select-brand', name: 'manage_category_select_brand')]
    public function newCategorySelectBrand(Request $request): Response
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
    public function newCategory(
        #[CurrentUser] User $user,
        Brand $brand,
        Request $request,
        CategoryRepository $repo,
        ModelRepository $mRepo
    ): Response {
        $category = new Category();

        if ($modelId = $request->get('model')) {
            if ($model = $mRepo->findOneById($modelId)) {
                $category->setModel($model);
            }
        }

        $form = $this->createForm(CategoryNewType::class, $category, ['brand' => $brand]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category
                ->setCreatedBy($user)
                ->setCreatedAt(new \DatetimeImmutable())
            ;

            $version = new Version();
            $version
                ->setMarkdown($category->getMarkdown())
                ->setName($category->getName())
                ->setAuthor($user)
                ->setCreatedAt(new \DatetimeImmutable())
                ->setVersion(1)
                ->setStatus(Version::STATUS_PUBLISHED)
            ;

            $category->addVersion($version);

            $repo->save($category, true);

            $this->addFlash(
                'success',
                'Category  ' . $category->getName() . ' successfully created.'
            );

            return $this->redirectToRoute('browse_category', ["id" => $category->getId()]);
        }

        return $this->render('manage/category-new.html.twig', [
            'form' => $form,
            'brand' => $brand
        ]);
    }

    #[Route('/manage/category/{id}/edit', name: 'manage_category_edit')]
    public function editCategory(
        Category $category,
        VersionManager $manager,
    ): Response {

        $version = $manager->getLastEntry($category);

        $form = $this->createForm(VersionType::class, $version);

        // $form->handleRequest($request);

        // if ($form->isSubmitted()) {
        //     // $submitted = $form->getData();
        //     // if (($category->getName() == $submitted->getName()) && ($category->getMarkdown() == $submitted->getMarkdown())) {
        //     //     $form->addError(new FormError('No modification submitted! Please change at least one field.'));
        //     // }

        //     if ($form->isValid()) {
        //         dump('valid');
        //         $repo->save($category, true);

        //         $this->addFlash(
        //             'success',
        //             'Category  ' . $category->getName() . ' successfully updated.'
        //         );

        //         return $this->redirectToRoute('manage_category_preview', ["id" => $category->getId()]);
        //     }
        // }

        return $this->render('manage/category-version-edit.html.twig', [
            'form' => $form,
            'category' => $category,
            'version' => $version,
        ]);
    }

    #[Route('/manage/category/{id}/publish-from-edit', name: 'manage_category_publish_from_edit')]
    public function publishCategoryVersionFromEdit(
        Category $category,
        VersionManager $manager,
        Request $request,
    ): Response {
        $version = $manager->getLastEntry($category);

        $form = $this->createForm(VersionType::class, $version);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($version->isIdentical()) {
                $this->addFlash('error', 'No modification submitted! Please change at least one field.');
                return $this->redirectToRoute('manage_category_edit', ["id" => $category->getId()]);
            }

            if ($form->isValid()) {
                $manager->publish($version);

                $this->addFlash(
                    'success',
                    'Category  ' . $category->getName() . ' successfully updated.'
                );

                return $this->redirectToRoute('manage_category_preview', ["id" => $category->getId()]);
            }
        }

        return $this->render('manage/category-preview.html.twig', [
            'category' => $category,
            'version' => $version,
        ]);
    }

    #[Route('/manage/category/{id}/preview', name: 'manage_category_preview')]
    public function categoryPreview(
        Category $category,
        VersionManager $manager,
        Request $request,
    ): Response {
        $version = $manager->getLastEntry($category);

        $form = $this->createForm(VersionType::class, $version);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($version->isIdentical()) {
                return $this->redirectToRoute('manage_category_preview', ["id" => $category->getId()]);
            }

            if ($form->isValid()) {
                $manager->saveDraft($version);

                return $this->redirectToRoute('manage_category_preview', ["id" => $category->getId()]);
            }
        }

        return $this->render('manage/category-preview.html.twig', [
            'category' => $category,
            'version' => $version,
        ]);
    }

    #[Route('/manage/category/{id}/publish-from-preview', name: 'manage_category_publish_from_preview')]
    public function categoryPublishFromPreview(
        Category $category,
        VersionManager $manager,
    ): Response {
        $version = $manager->getLastEntry($category);

        if ($version->isIdentical()) {
            $this->addFlash('error', 'No modification submitted! Please change at least one field.');
            return $this->redirectToRoute('manage_category_edit', ["id" => $category->getId()]);
        }

        $manager->publish($version);

        return $this->redirectToRoute('manage_category_preview', ["id" => $category->getId()]);
    }

    #[Route('/manage/category/{id}/delete-draft', name: 'manage_category_delete_draft')]
    public function categoryDeleteDraft(
        Category $category,
        VersionManager $manager,
    ): Response {
        $version = $manager->deleteDraft($category);

        return $this->redirectToRoute('manage_category_preview', ["id" => $category->getId()]);
    }
}
