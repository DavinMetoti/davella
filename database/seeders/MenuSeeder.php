<?php

namespace Database\Seeders;

use App\Models\Menu;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuItems = config('menu.items', []);

        foreach ($menuItems as $index => $item) {
            $permission = null;
            if ($item['permission']) {
                $permission = Permission::where('name', $item['permission'])->first();
            }

            Menu::create([
                'name' => $item['name'],
                'icon' => $item['icon'],
                'route' => $item['route'],
                'permission_id' => $permission ? $permission->id : null,
                'order' => $index,
                'is_active' => true,
            ]);
        }
    }
}
