<?php

namespace Modules\Service\database\seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Modules\Category\Models\Category;
use Modules\Service\Models\Service;
use Modules\Service\Models\ServiceBranches;
use Modules\Service\Models\ServiceEmployee;
use App\Models\Branch;
use App\Models\User;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks!
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        /*
         * Services Seed
         * ------------------
         */

        if (env('IS_DUMMY_DATA')) {
            $data = [
                // Hair Category Services
                [
                    'slug' => 'beard-trim',
                    'name' => 'Beard Trim',
                    'description' => 'Trim',
                    'duration_min' => 60,
                    'default_price' => 50.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 12.webp'),
                    'category' => 'hair',
                    'sub_category' => 'haircuts',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'men-haircut',
                    'name' => "Men's Haircut",
                    'description' => 'A stylish haircut for men',
                    'duration_min' => 30,
                    'default_price' => 400.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 10.webp'),
                    'category' => 'hair',
                    'sub_category' => 'haircuts',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'buzz-cut',
                    'name' => 'Buzz Cut',
                    'description' => 'Cut',
                    'duration_min' => 55,
                    'default_price' => 2000.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 24.webp'),
                    'category' => 'hair',
                    'sub_category' => 'haircuts',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'fade-cut',
                    'name' => 'Fade Cut',
                    'description' => 'Fade Cut',
                    'duration_min' => 60,
                    'default_price' => 100.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 19.webp'),
                    'category' => 'hair',
                    'sub_category' => 'haircuts',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'basic-trim',
                    'name' => 'Basic Trim',
                    'description' => 'Basic Trim',
                    'duration_min' => 50,
                    'default_price' => 100.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 25.webp'),
                    'category' => 'hair',
                    'sub_category' => 'haircuts',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'curling',
                    'name' => 'Curling',
                    'description' => 'Curling',
                    'duration_min' => 50,
                    'default_price' => 200.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 40.webp'),
                    'category' => 'hair',
                    'sub_category' => 'hairstyling',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'updo',
                    'name' => 'Updo',
                    'description' => 'Elegant updo for special occasions',
                    'duration_min' => 60,
                    'default_price' => 200.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 3.webp'),
                    'category' => 'hair',
                    'sub_category' => 'hairstyling',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'blowout',
                    'name' => 'Blowout',
                    'description' => 'Blowout',
                    'duration_min' => 45,
                    'default_price' => 200.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 8.webp'),
                    'category' => 'hair',
                    'sub_category' => 'hairstyling',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'long-layers',
                    'name' => 'Long Layers',
                    'description' => 'Long Layers',
                    'duration_min' => 50,
                    'default_price' => 500.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 35.webp'),
                    'category' => 'hair',
                    'sub_category' => 'haircuts',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'bob-cut',
                    'name' => 'Bob Cut',
                    'description' => 'Bob Cut',
                    'duration_min' => 60,
                    'default_price' => 500.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 8.webp'),
                    'category' => 'hair',
                    'sub_category' => 'haircuts',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => '    layered-cut',
                    'name' => '    Layered Cut',
                    'description' => '    Layered Cut',
                    'duration_min' => 60,
                    'default_price' => 400.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 40.webp'),
                    'category' => 'hair',
                    'sub_category' => 'haircuts',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                // Grooming Services Packages
                [
                    'slug' => '    straight-razor-shave',
                    'name' => 'Straight Razor Shave',
                    'description' => 'Straight Razor Shave',
                    'duration_min' => 60,
                    'default_price' => 400.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 12.webp'),
                    'category' => 'grooming',
                    'sub_category' => 'shaving',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => '    hydrating-treatment',
                    'name' => 'Hydrating TreatmentBeard Line Up',
                    'description' => 'Hydrating Treatment',
                    'duration_min' => 50,
                    'default_price' => 100.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 4.webp'),
                    'category' => 'grooming',
                    'sub_category' => 'shaving',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'deep-cleansing',
                    'name' => 'Deep Cleansing',
                    'description' => 'Deep Cleansing',
                    'duration_min' => 60,
                    'default_price' => 150.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 1.webp'),
                    'category' => 'grooming',
                    'sub_category' => 'facial',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],

                [
                    'slug' => 'back-and-neck-massage',
                    'name' => 'Back and Neck Massage',
                    'description' => 'Back and Neck Massage',
                    'duration_min' => 70,
                    'default_price' => 150.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 30.webp'),
                    'category' => 'grooming',
                    'sub_category' => 'relaxation',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'full-body-massage',
                    'name' => 'Full Body Massage',
                    'description' => 'Full Body Massage',
                    'duration_min' => 60,
                    'default_price' => 200.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 21.webp'),
                    'category' => 'grooming',
                    'sub_category' => 'relaxation',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],

                // Makeup Category Services
                [
                    'slug' => 'airbrush-makeup',
                    'name' => 'Airbrush Makeup',
                    'description' => 'Airbrush Makeup',
                    'duration_min' => 80,
                    'default_price' => 2500.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 34.webp'),
                    'category' => 'nail-care',
                    'sub_category' => 'pedicure',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'traditional-bridal-makeup',
                    'name' => 'Traditional Bridal Makeup',
                    'description' => 'Traditional Bridal Makeup',
                    'duration_min' => 80,
                    'default_price' => 2500.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 43.webp'),
                    'category' => 'nail-care',
                    'sub_category' => 'pedicure',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                // Scalp Category Services
                [
                    'slug' => 'scalp-massage',
                    'name' => 'Scalp Massage',
                    'description' => 'Scalp Massage',
                    'duration_min' => 80,
                    'default_price' => 700.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 41.webp'),
                    'category' => 'hair-treatments ',
                    'sub_category' => 'scalp-treatments',
                    'created_by' => null,
                    'updated_by' => null,
                ],
                [
                    'slug' => 'deep-conditioning',
                    'name' => 'Deep Conditioning',
                    'description' => 'Deep Conditioning',
                    'duration_min' => 80,
                    'default_price' => 800.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 39.webp'),
                    'category' => 'hair-treatments',
                    'sub_category' => 'scalp-treatments',
                    'created_by' => null,
                    'updated_by' => null,
                ],

                // Package Category Services
                [
                    'slug' => 'facial',
                    'name' => 'Facial',
                    'description' => 'Facial',
                    'duration_min' => 120,
                    'default_price' => 300.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 44.webp'),
                    'category' => 'spa-packages',
                    'sub_category' => 'relaxation-package',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
                [
                    'slug' => 'full-body-massage',
                    'name' => 'Full Body Massage',
                    'description' => 'Full Body Massage',
                    'duration_min' => 120,
                    'default_price' => 400.00,
                    'status' => 1,
                    'feature_image' => public_path('/dummy-images/common/Service 27.webp'),
                    'category' => 'spa-packages',
                    'sub_category' => 'relaxation-package',
                    'created_by' => 2,
                    'updated_by' => 2,
                ],
            ];
            foreach ($data as $key => $value) {
                $categroy = Category::where('slug', $value['category'])->first();
                $sub_category = $value['sub_category'];
                $featureImage = $value['feature_image'] ?? null;
                $serviceData = Arr::except($value, ['sub_category', 'category', 'feature_image']);

                if (isset($sub_category)) {
                    $sub_category = Category::where('slug', $value['sub_category'])->first();
                }

                $service = [
                    'slug' => $value['slug'],
                    'name' => $value['name'],
                    'category_id' => $categroy->id,
                    'sub_category_id' => $sub_category->id ?? null,
                    'description' => $value['description'],
                    'duration_min' => $value['duration_min'],
                    'default_price' => $value['default_price'],
                    'status' => $value['status'],
                    'created_by' => $value['created_by'],
                    'updated_by' => $value['updated_by'],
                ];
                $service = Service::create($service);
                if (isset($featureImage)) {
                    $this->attachFeatureImage($service, $featureImage);
                }
                $branches = Branch::all();
                foreach ($branches as $branch) {
                    ServiceBranches::create([
                        'service_id' => $service->id,
                        'branch_id' => $branch->id,
                        'service_price' => $service->default_price ?? 0,
                        'duration_min' => $service->duration_min,
                        'created_by' => $branch->created_by == $service->created_by && $service->created_by !== null ? $service->created_by : null,
                        'updated_by' => $branch->created_by == $service->created_by && $service->created_by !== null ? $service->updated_by : null,
                    ]);
                }

                // Assign employees to the service
                $employees = User::with('mainBranch')->where('user_type', 'employee')->get();
                foreach ($employees as $employee) {
                    $serviceEmployee = ServiceEmployee::create([
                        'service_id' => $service->id,
                        'employee_id' => $employee->id,
                    ]);

                    // Retrieve the main branch of the employee
                    $mainBranch = $employee->mainBranch()->first();
                    if ($mainBranch) {
                        $serviceEmployee->update([
                            'created_by' => $mainBranch->created_by,
                            'updated_by' => $mainBranch->updated_by,
                        ]);
                    }
                }
            }
        }
        // Enable foreign key checks!
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    private function attachFeatureImage($model, $publicPath)
    {
        if (! env('IS_DUMMY_DATA_IMAGE')) {
            return false;
        }

        $file = new \Illuminate\Http\File($publicPath);

        $media = $model->addMedia($file)->preservingOriginal()->toMediaCollection('feature_image');

        return $media;
    }
}
