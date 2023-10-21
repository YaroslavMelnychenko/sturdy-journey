<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admins = [
            [
                'name' => 'Pashkatt',
                'email' => 'admin@pashkatt.pp.ua',
                'password' => 'secret',
            ],
        ];

        foreach ($admins as $admin) {
            Admin::create($admin);
        }
    }
}
