<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Exchanges',
            'Swap Online',
            'Wallets',
            'DeFi Exchanges',
            'Crypto swap apps',
            'Trading useful',
            'Aggregators',
            'Blockchain Platforms',
            'Mining & Staking Services',
            'Token & ICO Platforms',
            'Crypto Payment Solutions',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
            ]);
        }
    }
}
