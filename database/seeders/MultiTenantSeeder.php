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
            ['name' => 'Foo Company', 'slug' => 'foo', 'domain' => 'foo.example.com'],
            ['name' => 'Baz Company', 'slug' => 'baz', 'domain' => 'baz.example.com'],
            ['name' => 'Qux Company', 'slug' => 'qux', 'domain' => 'qux.example.com'],
            ['name' => 'Bar Company', 'slug' => 'bar', 'domain' => 'bar.example.com'],
        ];

        foreach ($tenants as $tenantData) {
            $tenant = Tenant::create($tenantData);
            $users = [];
            for ($u = 1; $u <= 2; $u++) {
                $users[] = User::create([
                    'name'      => "{$tenant->slug} User {$u}",
                    'email'     => "{$tenant->slug}user{$u}@example.com",
                    'password'  => Hash::make('password'),
                    'tenant_id' => $tenant->id,
                ]);
            }

            for ($c = 1; $c <= 2; $c++) {
                $category = Category::create([
                    'name'      => ucfirst($tenant->slug) . " Category {$c}",
                    'slug'      => "{$tenant->slug}-category-{$c}",
                    'tenant_id' => $tenant->id,
                ]);

                for ($p = 1; $p <= 3; $p++) {
                    Post::create([
                        'title'        => ucfirst($tenant->slug) . " Post {$p} (Cat {$c})",
                        'content'         => "{$tenant->slug}-post-content-{$c}-{$p}",
                        'slug'         => "{$tenant->slug}-post-{$c}-{$p}",
                        'image_path' => null,
                        'category_id'  => $category->id,
                        'tenant_id'    => $tenant->id,
                        'created_by'   => $users[0]->id,
                        'updated_by'   => $users[0]->id,
                    ]);
                }
            }
        }
    }
}
