<?php

namespace App\Repository\Interface;

use App\Models\Content;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContentRepoInterface
{
    public function getAll(array $allowedTiers, int $perPage = 10, ?string $search = null, string $sort = 'desc', ?string $sortBy = null): LengthAwarePaginator;
    public function findById(Content $content): ?Content;
    public function create(array $data): Content;
    public function update(Content $content, array $data): bool;
    public function delete(Content $content): bool;
}
