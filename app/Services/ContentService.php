<?php

namespace App\Services;

use App\Repository\Interface\ContentRepoInterface;
use App\Models\Content;
use Illuminate\Pagination\LengthAwarePaginator;

class ContentService
{
    protected $contentRepo;

    public function __construct(ContentRepoInterface $contentRepo)
    {
        $this->contentRepo = $contentRepo;
    }

    public function getAllContents(int $perPage = 10): LengthAwarePaginator
    {
        return $this->contentRepo->getAll($perPage);
    }

    public function getContentById(Content $content): ?Content
    {
        return $this->contentRepo->findById($content);
    }

    public function createContent(array $data): Content
    {
        return $this->contentRepo->create($data);
    }

    public function updateContent(Content $content, array $data): bool
    {
        return $this->contentRepo->update($content, $data);
    }

    public function deleteContent(Content $content): bool
    {
        return $this->contentRepo->delete($content);
    }
}
