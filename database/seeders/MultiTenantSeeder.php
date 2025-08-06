<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\{Tenant, User, Category, Post};
class MultiTenantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tenants = [
            ['id' => 'tenant'],
            ['id' => 'foo'],
        ];

        foreach ($tenants as $key => $tenantData) {
            $tenant = Tenant::create($tenantData);
            if($key==0){
                $tenant->domains()->create(['domain' => 'tenant.localhost']);
            }else{
                $tenant->domains()->create(['domain' => 'foo.localhost']);
            }

            //Initialize tenant context (activate this tenant)
            $users = [];
            for ($u = 1; $u <= 2; $u++) {
                $users[] = User::create([
                    'name'      => "{$tenant->id} User {$u}",
                    'email'     => "{$tenant->id}user{$u}@user.com",
                    'password'  => Hash::make('password'),
                    'tenant_id' => $tenant->id,
                ]);
            }

            for ($c = 1; $c <= 2; $c++) {
                $category = Category::create([
                    'name'      => ucfirst($tenant->id) . " Category {$c}",
                    'slug'      => "{$tenant->id}-category-{$c}",
                    'tenant_id' => $tenant->id,
                ]);

                for ($p = 1; $p <= 3; $p++) {
                    Post::create([
                        'title'        => ucfirst($tenant->id) . " Post {$p} (Cat {$c})",
                        'content'         => "{$tenant->id}-post-content-{$c}-{$p}",
                        'slug'         => "{$tenant->id}-post-{$c}-{$p}",
                        'image_path' => null,
                        'category_id'  => $category->id,
                        'tenant_id'    => $tenant->id,
                        'created_by'   => $users[0]->id,
                        'updated_by'   => $users[0]->id,
                    ]);
                }
            }
            //End tenant context (deactivate)
            tenancy()->end();
        }
    }
}
