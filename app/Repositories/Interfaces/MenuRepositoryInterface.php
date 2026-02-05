<?php

namespace App\Repositories\Interfaces;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

interface MenuRepositoryInterface
{
    public function getAll(): Collection;
    public function findById(int $id): ?Menu;
    public function create(array $data): Menu;
    public function update(int $id, array $data): bool;
    public function delete(int $id): bool;
    public function getActiveMenus(): Collection;
}