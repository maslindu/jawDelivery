<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;
use App\Models\Category;

class MenuSeeder extends Seeder
{
    public function run()
    {
        // Create categories first
        $categories = [
            'Makanan Utama',
            'Minuman',
            'Snack',
            'Dessert'
        ];

        foreach ($categories as $categoryName) {
            Category::firstOrCreate(['name' => $categoryName]);
        }

        $menus = [
            [
                'name' => 'Nasi Goreng Spesial',
                'price' => 25000,
                'stock' => 50,
                'description' => 'Nasi goreng dengan telur, ayam, dan kerupuk.',
                'image_link' => null,
                'featured' => true,
            ],
            [
                'name' => 'Ayam Geprek Sambal Bawang',
                'price' => 22000,
                'stock' => 30,
                'description' => 'Ayam geprek dengan sambal bawang pedas dan nasi putih.',
                'image_link' => null,
                'featured' => false,
            ],
            [
                'name' => 'Sate Ayam Madura',
                'price' => 30000,
                'stock' => 40,
                'description' => '10 tusuk sate ayam bumbu kacang khas Madura.',
                'image_link' => null,
                'featured' => false,
            ],
            [
                'name' => 'Mie Goreng Jawa',
                'price' => 20000,
                'stock' => 25,
                'description' => 'Mie goreng tradisional dengan sayur dan ayam.',
                'image_link' => null,
                'featured' => true,
            ],
            [
                'name' => 'Es Teh Manis',
                'price' => 5000,
                'stock' => 100,
                'description' => 'Minuman segar teh manis dingin.',
                'image_link' => null,
                'featured' => false,
            ]
        ];

        foreach ($menus as $menuData) {
            Menu::firstOrCreate(
                ['name' => $menuData['name']],
                $menuData
            );
        }
    }
}
