<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category; // Pastikan model Category di-import

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Sofa', 
            'Table', 
            'Chair', 
            'Lighting', 
            'Bed', 
            'Cabinet', 
            'Desk', 
            'Shelf', 
            'Outdoor Furniture'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                // Tambahkan 'description' => '...' di sini jika tabelmu mewajibkannya
            ]);
        }
    }
}