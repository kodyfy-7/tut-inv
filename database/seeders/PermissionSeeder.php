<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class PermissionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $permissions = [
            ['name' => 'category-index', 'route_name' => 'categories.index'],
            ['name' => 'category-store', 'route_name' => 'categories.store'],
            ['name' => 'category-update', 'route_name' => 'categories.update'],
            ['name' => 'category-delete', 'route_name' => 'categories.delete'],
        ];

        collect($permissions)->each(function ($permission) {
            Permission::create($permission);
        });
    }
}
