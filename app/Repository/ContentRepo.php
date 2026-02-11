<?php

namespace App\Repository;

use App\Models\Content;
use App\Repository\Interface\ContentRepoInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ContentRepo implements ContentRepoInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Content::with('creator')->paginate($perPage);
    }

    public function findById(Content $content): ?Content
    {
        return $content->fresh();
    }

    public function create(array $data): Content
    {
        return Content::create($data);
    }

    public function update(Content $content, array $data): bool
    {
        return $content->update($data);
    }

    public function delete(Content $content): bool
    {
        return $content->delete();
    }
}
