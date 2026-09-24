<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics & Gadgets',
                'icon' => 'device-phone-mobile',
                'description' => 'Smartphones, laptops, tablets, earbuds, chargers, and smartwatches.',
            ],
            [
                'name' => 'Wallets, Purses & Bags',
                'icon' => 'briefcase',
                'description' => 'Wallets, clutches, backpacks, tote bags, and luggage.',
            ],
            [
                'name' => 'Keys & Access Badges',
                'icon' => 'key',
                'description' => 'House keys, car key fobs, building access cards, and keychains.',
            ],
            [
                'name' => 'Documents & IDs',
                'icon' => 'identification',
                'description' => 'Passports, driver licenses, student badges, and bank cards.',
            ],
            [
                'name' => 'Pets & Animals',
                'icon' => 'heart',
                'description' => 'Dogs, cats, birds, and other missing or rescued animals.',
            ],
            [
                'name' => 'Jewelry & Watches',
                'icon' => 'sparkles',
                'description' => 'Watches, rings, necklaces, bracelets, and precious items.',
            ],
            [
                'name' => 'Clothing & Eyewear',
                'icon' => 'tag',
                'description' => 'Jackets, prescription glasses, sunglasses, hats, and scarves.',
            ],
            [
                'name' => 'Books & Stationery',
                'icon' => 'book-open',
                'description' => 'Notebooks, textbooks, binders, and art supplies.',
            ],
            [
                'name' => 'Sports & Mobility',
                'icon' => 'truck',
                'description' => 'Bicycles, scooters, skateboards, gym bags, and sporting gear.',
            ],
            [
                'name' => 'Other Miscellaneous',
                'icon' => 'archive-box',
                'description' => 'Any items not covered by other categories.',
            ],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => Str::slug($cat['name'])],
                [
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'description' => $cat['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
