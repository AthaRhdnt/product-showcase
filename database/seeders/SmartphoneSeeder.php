<?php
namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SmartphoneSeeder extends Seeder
{
    private function generateUniqueSlug(string $name): string
    {
        $baseSlug = Str::slug($name);
        $slug     = $baseSlug;
        $counter  = 2;

        while (\App\Models\Product::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Categories (Brands)
        $brands = ['Apple', 'Samsung', 'Google', 'OnePlus', 'Xiaomi'];
        $categories = [];

        foreach ($brands as $brand) {
            $categories[$brand] = Category::create([
                'name' => $brand,
                'slug' => Str::slug($brand),
            ]);
        }

        // Define attributes
        $attributes = [
            'Storage', 'RAM', 'Battery', 'Processor', 'Screen Size',
            'Camera', 'Operating System', 'Weight', 'Material', 'Refresh Rate',
        ];

        $attributeIds = [];

        foreach ($attributes as $attr) {
            $attribute           = Attribute::create(['name' => $attr]);
            $attributeIds[$attr] = $attribute->id;
        }

        // Products for each brand
        $productsByBrand = [
            'Apple'   => [
                [
                    'name'        => 'iPhone 15 Pro',
                    'description' => 'Experience the power of Apple\'s A17 Pro chip with stunning camera and sleek titanium design.',
                    'price'       => 19990000,
                    'attributes'  => [
                        'Storage'          => '256GB',
                        'RAM'              => '8GB',
                        'Battery'          => '3274mAh',
                        'Processor'        => 'A17 Pro',
                        'Screen Size'      => '6.1 inches',
                        'Camera'           => '48MP + 12MP',
                        'Operating System' => 'iOS 17',
                        'Weight'           => '187g',
                        'Material'         => 'Titanium',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'iPhone 15',
                    'description' => 'A refined classic with vibrant colors, smooth performance, and long-lasting battery life.',
                    'price'       => 14990000,
                    'attributes'  => [
                        'Storage'          => '128GB',
                        'RAM'              => '6GB',
                        'Battery'          => '3349mAh',
                        'Processor'        => 'A16 Bionic',
                        'Screen Size'      => '6.1 inches',
                        'Camera'           => '48MP + 12MP',
                        'Operating System' => 'iOS 17',
                        'Weight'           => '171g',
                        'Material'         => 'Aluminum',
                        'Refresh Rate'     => '60Hz',
                    ],
                ],
                [
                    'name'        => 'iPhone 15 Plus',
                    'description' => 'A larger screen and battery make this iPhone the perfect media and productivity companion.',
                    'price'       => 16990000,
                    'attributes'  => [
                        'Storage'          => '256GB',
                        'RAM'              => '6GB',
                        'Battery'          => '4383mAh',
                        'Processor'        => 'A16 Bionic',
                        'Screen Size'      => '6.7 inches',
                        'Camera'           => '48MP + 12MP',
                        'Operating System' => 'iOS 17',
                        'Weight'           => '201g',
                        'Material'         => 'Aluminum',
                        'Refresh Rate'     => '60Hz',
                    ],
                ],
            ],
            'Samsung' => [
                [
                    'name'        => 'Galaxy S24 Ultra',
                    'description' => 'Samsung\'s most powerful phone yet with a crystal-clear 200MP camera and stylus support.',
                    'price'       => 18999000,
                    'attributes'  => [
                        'Storage'          => '512GB',
                        'RAM'              => '12GB',
                        'Battery'          => '5000mAh',
                        'Processor'        => 'Snapdragon 8 Gen 3',
                        'Screen Size'      => '6.8 inches',
                        'Camera'           => '200MP + 12MP + 10MP + 10MP',
                        'Operating System' => 'Android 14',
                        'Weight'           => '233g',
                        'Material'         => 'Titanium',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'Galaxy S24+',
                    'description' => 'A premium smartphone with high performance and immersive visuals in a compact frame.',
                    'price'       => 15999000,
                    'attributes'  => [
                        'Storage'          => '256GB',
                        'RAM'              => '12GB',
                        'Battery'          => '4900mAh',
                        'Processor'        => 'Snapdragon 8 Gen 3',
                        'Screen Size'      => '6.7 inches',
                        'Camera'           => '50MP + 12MP + 10MP',
                        'Operating System' => 'Android 14',
                        'Weight'           => '196g',
                        'Material'         => 'Aluminum',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'Galaxy S24',
                    'description' => 'A compact flagship with intelligent AI features and excellent all-day battery life.',
                    'price'       => 13999000,
                    'attributes'  => [
                        'Storage'          => '128GB',
                        'RAM'              => '8GB',
                        'Battery'          => '4000mAh',
                        'Processor'        => 'Snapdragon 8 Gen 3',
                        'Screen Size'      => '6.1 inches',
                        'Camera'           => '50MP + 12MP + 10MP',
                        'Operating System' => 'Android 14',
                        'Weight'           => '168g',
                        'Material'         => 'Aluminum',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
            ],
            'Google'  => [
                [
                    'name'        => 'Pixel 8 Pro',
                    'description' => 'A photography powerhouse with AI-enhanced features and clean Android experience.',
                    'price'       => 16999000,
                    'attributes'  => [
                        'Storage'          => '128GB',
                        'RAM'              => '12GB',
                        'Battery'          => '5050mAh',
                        'Processor'        => 'Google Tensor G3',
                        'Screen Size'      => '6.7 inches',
                        'Camera'           => '50MP + 48MP + 48MP',
                        'Operating System' => 'Android 14',
                        'Weight'           => '213g',
                        'Material'         => 'Aluminum',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'Pixel 8',
                    'description' => 'The perfect blend of performance and simplicity with a brilliant display and smart features.',
                    'price'       => 12999000,
                    'attributes'  => [
                        'Storage'          => '128GB',
                        'RAM'              => '8GB',
                        'Battery'          => '4575mAh',
                        'Processor'        => 'Google Tensor G3',
                        'Screen Size'      => '6.2 inches',
                        'Camera'           => '50MP + 12MP',
                        'Operating System' => 'Android 14',
                        'Weight'           => '187g',
                        'Material'         => 'Aluminum',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'Pixel 7a',
                    'description' => 'Affordable yet powerful with Google\'s signature camera software and smooth performance.',
                    'price'       => 7499000,
                    'attributes'  => [
                        'Storage'          => '128GB',
                        'RAM'              => '8GB',
                        'Battery'          => '4385mAh',
                        'Processor'        => 'Google Tensor G2',
                        'Screen Size'      => '6.1 inches',
                        'Camera'           => '64MP + 13MP',
                        'Operating System' => 'Android 13',
                        'Weight'           => '193g',
                        'Material'         => 'Plastic Frame',
                        'Refresh Rate'     => '90Hz',
                    ],
                ],
            ],
            // Additional Brands and Products
            'OnePlus' => [
                [
                    'name'        => 'OnePlus 12',
                    'description' => 'Flagship killer with Snapdragon 8 Gen 3 and ultra-fast charging capabilities.',
                    'price'       => 12999000,
                    'attributes'  => [
                        'Storage'          => '256GB',
                        'RAM'              => '12GB',
                        'Battery'          => '5400mAh',
                        'Processor'        => 'Snapdragon 8 Gen 3',
                        'Screen Size'      => '6.82 inches',
                        'Camera'           => '50MP + 64MP + 48MP',
                        'Operating System' => 'Android 14',
                        'Weight'           => '220g',
                        'Material'         => 'Glass',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'OnePlus 12R with Advanced Cooling',
                    'description' => 'An affordable performance beast with efficient thermal design and smooth multitasking.',
                    'price'       => 8999000,
                    'attributes'  => [
                        'Storage'          => '128GB',
                        'RAM'              => '8GB',
                        'Battery'          => '5000mAh',
                        'Processor'        => 'Snapdragon 8 Gen 2',
                        'Screen Size'      => '6.78 inches',
                        'Camera'           => '50MP + 8MP + 2MP',
                        'Operating System' => 'Android 14',
                        'Weight'           => '207g',
                        'Material'         => 'Aluminum',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'OnePlus Nord CE 3 Lite 5G',
                    'description' => 'Affordable 5G smartphone with solid performance and a large display.',
                    'price'       => 3999000,
                    'attributes'  => [
                        'Storage'          => '128GB',
                        'RAM'              => '8GB',
                        'Battery'          => '5000mAh',
                        'Processor'        => 'Snapdragon 695',
                        'Screen Size'      => '6.72 inches',
                        'Camera'           => '108MP + 2MP + 2MP',
                        'Operating System' => 'Android 13',
                        'Weight'           => '195g',
                        'Material'         => 'Plastic',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'OnePlus Nord 3 5G Performance Edition',
                    'description' => 'Designed for gamers and creators with MediaTek Dimensity 9000 chipset.',
                    'price'       => 6999000,
                    'attributes'  => [
                        'Storage'          => '256GB',
                        'RAM'              => '16GB',
                        'Battery'          => '5000mAh',
                        'Processor'        => 'Dimensity 9000',
                        'Screen Size'      => '6.74 inches',
                        'Camera'           => '50MP + 8MP + 2MP',
                        'Operating System' => 'Android 13',
                        'Weight'           => '193g',
                        'Material'         => 'Glass',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
            ],
            'Xiaomi'  => [
                [
                    'name'        => 'Xiaomi 14 Ultra Titanium Edition',
                    'description' => 'Photography flagship with Leica lenses and durable titanium body.',
                    'price'       => 16999000,
                    'attributes'  => [
                        'Storage'          => '512GB',
                        'RAM'              => '16GB',
                        'Battery'          => '5300mAh',
                        'Processor'        => 'Snapdragon 8 Gen 3',
                        'Screen Size'      => '6.73 inches',
                        'Camera'           => '50MP quad camera system',
                        'Operating System' => 'HyperOS (Android 14)',
                        'Weight'           => '220g',
                        'Material'         => 'Titanium',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'Xiaomi 14 Pro',
                    'description' => 'Elegant design meets raw power with blazing fast performance and stunning display.',
                    'price'       => 14999000,
                    'attributes'  => [
                        'Storage'          => '256GB',
                        'RAM'              => '12GB',
                        'Battery'          => '4880mAh',
                        'Processor'        => 'Snapdragon 8 Gen 3',
                        'Screen Size'      => '6.73 inches',
                        'Camera'           => '50MP triple camera',
                        'Operating System' => 'HyperOS (Android 14)',
                        'Weight'           => '223g',
                        'Material'         => 'Aluminum',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'Redmi Note 13 Pro+ 5G Premium Glass Edition',
                    'description' => 'Mid-range champion with curved AMOLED display and excellent build quality.',
                    'price'       => 5799000,
                    'attributes'  => [
                        'Storage'          => '256GB',
                        'RAM'              => '12GB',
                        'Battery'          => '5000mAh',
                        'Processor'        => 'Dimensity 7200 Ultra',
                        'Screen Size'      => '6.67 inches',
                        'Camera'           => '200MP + 8MP + 2MP',
                        'Operating System' => 'MIUI 14 (Android 13)',
                        'Weight'           => '204g',
                        'Material'         => 'Glass',
                        'Refresh Rate'     => '120Hz',
                    ],
                ],
                [
                    'name'        => 'Redmi Note 13',
                    'description' => 'Affordable phone with smooth display and decent all-around specs for everyday use.',
                    'price'       => 2999000,
                    'attributes'  => [
                        'Storage'          => '128GB',
                        'RAM'              => '6GB',
                        'Battery'          => '5000mAh',
                        'Processor'        => 'Snapdragon 685',
                        'Screen Size'      => '6.6 inches',
                        'Camera'           => '50MP + 2MP',
                        'Operating System' => 'MIUI 14 (Android 13)',
                        'Weight'           => '188g',
                        'Material'         => 'Plastic',
                        'Refresh Rate'     => '90Hz',
                    ],
                ],
            ],
        ];

        foreach ($productsByBrand as $brand => $products) {
            foreach ($products as $item) {
                $product = Product::create([
                    'name'        => $item['name'],
                    'slug'        => $this->generateUniqueSlug($item['name']),
                    'description' => $item['description'],
                    'price'       => $item['price'],
                    'category_id' => $categories[$brand]->id,
                    'thumbnail'   => null,
                ]);

                foreach ($item['attributes'] as $attrName => $value) {
                    ProductAttribute::create([
                        'product_id'   => $product->id,
                        'attribute_id' => $attributeIds[$attrName],
                        'value'        => $value,
                    ]);
                }
            }
        }
    }
}
