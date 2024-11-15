<?php

namespace App\Service\Category;

use App\Entity\Speedbuilding\Category;
use App\Entity\Speedbuilding\CategoryVersion as Version;
use App\Repository\Speedbuilding\CategoryRepository;
use App\Repository\Speedbuilding\CategoryVersionRepository;
use Symfony\Bundle\SecurityBundle\Security;


/**
 * Small helper to bundle different Category <-> Version logic
 */
class VersionManager
{
    private $author;

    public function __construct(
        private CategoryVersionRepository $vRepo,
        private CategoryRepository $cRepo,
        Security $security
    ){
        $this->author = $security->getUser();
    }

    public function getLastEntry(Category $category): Version
    {
        $lastVersion = $this->vRepo->getLastEntry($category);

        if ($lastVersion->isPublished()) {
            $version = new Version;

            $version
                ->setCategory($lastVersion->getCategory())
                ->setName($lastVersion->getName())
                ->setMarkdown($lastVersion->getMarkdown())
                ->setAuthor($lastVersion->getAuthor())
                ->setVersion($lastVersion->getVersion() + 1)
            ;

            return $version;
        }

        return $lastVersion;
    }

    public function saveDraft(Version $version): void
    {
        $version
            ->setStatus(Version::STATUS_DRAFT)
            ->setCreatedAt(new \DatetimeImmutable())
        ;

        $this->vRepo->save($version);
    }

    public function publish(Version $version): void
    {
        $version
            ->setAuthor($this->author)
            ->setCreatedAt(new \DatetimeImmutable())
            ->setStatus(Version::STATUS_PUBLISHED)
        ;

        $version->getCategory()
            ->setName($version->getName())
            ->setMarkdown($version->getMarkdown())
        ;

        $this->cRepo->save($version->getCategory());
    }

    public function deleteDraft(Category $category)
    {

        $this->vRepo->deleteDraft($category);
    }
}
