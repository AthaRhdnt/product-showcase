<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SmartphoneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories
        $category = Category::create([
            'name' => 'Smartphones',
            'slug' => Str::slug('Smartphones'),
        ]);

        // Define attributes
        $attributes = [
            'Storage', 'RAM', 'Battery', 'Processor', 'Screen Size'
        ];

        $attributeIds = [];

        foreach ($attributes as $attr) {
            $attribute = Attribute::create(['name' => $attr]);
            $attributeIds[$attr] = $attribute->id;
        }

        // Products with their attributes
        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Apple iPhone 15 Pro with A17 Pro chip and 256GB storage.',
                'price' => 19990000,
                'thumbnail' => 'iphone15pro.jpg',
                'attributes' => [
                    'Storage' => '256GB',
                    'RAM' => '8GB',
                    'Battery' => '3274mAh',
                    'Processor' => 'A17 Pro',
                    'Screen Size' => '6.1 inches'
                ]
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra',
                'description' => 'Samsung flagship with Snapdragon 8 Gen 3 and 12GB RAM.',
                'price' => 18999000,
                'thumbnail' => 'galaxys24ultra.jpg',
                'attributes' => [
                    'Storage' => '512GB',
                    'RAM' => '12GB',
                    'Battery' => '5000mAh',
                    'Processor' => 'Snapdragon 8 Gen 3',
                    'Screen Size' => '6.8 inches'
                ]
            ],
            [
                'name' => 'Google Pixel 8 Pro',
                'description' => 'Google Pixel 8 Pro with Tensor G3 chip and clean Android experience.',
                'price' => 16999000,
                'thumbnail' => 'pixel8pro.jpg',
                'attributes' => [
                    'Storage' => '128GB',
                    'RAM' => '12GB',
                    'Battery' => '5050mAh',
                    'Processor' => 'Tensor G3',
                    'Screen Size' => '6.7 inches'
                ]
            ],
        ];

        foreach ($products as $item) {
            $product = Product::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'description' => $item['description'],
                'price' => $item['price'],
                'category_id' => $category->id,
                'thumbnail' => $item['thumbnail']
            ]);

            foreach ($item['attributes'] as $attrName => $value) {
                ProductAttribute::create([
                    'product_id' => $product->id,
                    'attribute_id' => $attributeIds[$attrName],
                    'value' => $value
                ]);
            }
        }
    }
}
