<?php

namespace App\Repository\Interface;

use App\Models\Content;
use Illuminate\Pagination\LengthAwarePaginator;

interface ContentRepoInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator;
    public function findById(Content $content): ?Content;
    public function create(array $data): Content;
    public function update(Content $content, array $data): bool;
    public function delete(Content $content): bool;
}
