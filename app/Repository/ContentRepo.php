<?php

namespace App\Repository;

use App\Models\Content;
use App\Repository\Interface\ContentRepoInterface;
use Illuminate\Pagination\LengthAwarePaginator;

class ContentRepo implements ContentRepoInterface
{
    public function getAll(array $allowedTiers, int $perPage = 10, ?string $search = null, string $sort = 'desc', ?string $sortBy = null): LengthAwarePaginator
    {
        $query = Content::query();

        // Filter by allowed tiers
        $query->whereIn('tier', $allowedTiers);

        // search filter
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }

        // Apply sorting
        switch ($sortBy) {
            case 'title':
                $query->orderBy('title', $sort);
                break;
            case 'tier':
                $query->orderBy('tier', $sort);
                break;
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        return $query->paginate($perPage);
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
