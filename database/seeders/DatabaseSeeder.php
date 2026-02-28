<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $acme = Tenant::query()->create([
            'name' => 'Acme Inc.',
            'slug' => 'acme',
        ]);

        $globex = Tenant::query()->create([
            'name' => 'Globex Corp.',
            'slug' => 'globex',
        ]);

        $owner = User::query()->create([
            'name' => 'Owner User',
            'email' => 'owner@forgebase.test',
            'password' => Hash::make('password'),
        ]);

        $admin = User::query()->create([
            'name' => 'Admin User',
            'email' => 'admin@forgebase.test',
            'password' => Hash::make('password'),
        ]);

        $member = User::query()->create([
            'name' => 'Member User',
            'email' => 'member@forgebase.test',
            'password' => Hash::make('password'),
        ]);

        DB::table('tenant_user')->insert([
            ['tenant_id' => $acme->id, 'user_id' => $owner->id, 'role' => 'owner'],
            ['tenant_id' => $acme->id, 'user_id' => $admin->id, 'role' => 'admin'],
            ['tenant_id' => $acme->id, 'user_id' => $member->id, 'role' => 'member'],
            ['tenant_id' => $globex->id, 'user_id' => $owner->id, 'role' => 'owner'],
            ['tenant_id' => $globex->id, 'user_id' => $admin->id, 'role' => 'admin'],
            ['tenant_id' => $globex->id, 'user_id' => $member->id, 'role' => 'member'],
        ]);

        $now = now();

        DB::table('projects')->insert([
            ['tenant_id' => $acme->id, 'name' => 'Acme CRM Revamp', 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $acme->id, 'name' => 'Acme Q2 Launch', 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $acme->id, 'name' => 'Acme Ops Dashboard', 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $globex->id, 'name' => 'Globex Mobile App', 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $globex->id, 'name' => 'Globex Billing Upgrade', 'created_at' => $now, 'updated_at' => $now],
            ['tenant_id' => $globex->id, 'name' => 'Globex Analytics Hub', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }
}
