<?php

namespace App\Repositories;

use App\Models\Menu;
use App\Repositories\Interfaces\MenuRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository implements MenuRepositoryInterface
{
    public function getAll(): Collection
    {
        return Menu::with(['permission', 'children'])->ordered()->get();
    }

    public function findById(int $id): ?Menu
    {
        return Menu::with(['permission', 'children'])->find($id);
    }

    public function create(array $data): Menu
    {
        return Menu::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $menu = Menu::find($id);
        return $menu ? $menu->update($data) : false;
    }

    public function delete(int $id): bool
    {
        $menu = Menu::find($id);
        return $menu ? $menu->delete() : false;
    }

    public function getActiveMenus(): Collection
    {
        return Menu::active()->whereNull('parent_id')->with('children')->ordered()->get();
    }
}