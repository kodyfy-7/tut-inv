<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Schema::disableForeignKeyConstraints();
        DB::table('users')->truncate();
        Schema::enableForeignKeyConstraints();

        $user = User::create([
            'username' => 'vendor1',
            'email' => 'one@vendor.com',
            'password' => Hash::make('password'),
            'role_id' => 1,
        ]);

        Supplier::create([
            'user_id' => $user->id,
            'name' => 'Vendor',
            'phone' => '0909090909'
        ]);
    }
}
