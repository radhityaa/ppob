<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();

        try {
            $user = $this->createUser();

            $this->createRole($user);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
        }
    }

    public function createRole($user)
    {
        $role_admin = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $role_reseller = Role::create(['name' => 'reseller', 'guard_name' => 'web']);
        $role_agen = Role::create(['name' => 'agen', 'guard_name' => 'web']);
        $role_member = Role::create(['name' => 'member', 'guard_name' => 'web']);

        $permissions = ['read', 'create', 'update', 'delete'];

        foreach ($permissions as $permission) {
            $permissionKonfig =  $permission . ' ' . 'konfigurasi';
            $permissionPerm =  $permission . ' ' . 'konfigurasi/permissions';
            $permissionRole =  $permission . ' ' . 'konfigurasi/roles';
            $permissionNav =  $permission . ' ' . 'konfigurasi/navigation';
            $permissionUser =  $permission . ' ' . 'users';
            $permissionUserCreate =  $permission . ' ' . 'users/create';
            $permissionUserUpdate =  $permission . ' ' . 'users/update';
            $permissionUserDelete =  $permission . ' ' . 'users/delete';

            Permission::firstOrCreate(['name' => $permissionKonfig]);
            Permission::firstOrCreate(['name' => $permissionPerm]);
            Permission::firstOrCreate(['name' => $permissionRole]);
            Permission::firstOrCreate(['name' => $permissionNav]);
            Permission::firstOrCreate(['name' => $permissionUser]);
            Permission::firstOrCreate(['name' => $permissionUserCreate]);
            Permission::firstOrCreate(['name' => $permissionUserUpdate]);
            Permission::firstOrCreate(['name' => $permissionUserDelete]);

            $role_admin->givePermissionTo($permissionKonfig);
            $role_admin->givePermissionTo($permissionPerm);
            $role_admin->givePermissionTo($permissionRole);
            $role_admin->givePermissionTo($permissionNav);
            $role_admin->givePermissionTo($permissionUser);
            $role_admin->givePermissionTo($permissionUserCreate);
            $role_admin->givePermissionTo($permissionUserUpdate);
            $role_admin->givePermissionTo($permissionUserDelete);
        }

        $user['admin']->assignRole('admin');
        $user['reseller']->assignRole('reseller');
        $user['agen']->assignRole('agen');
        $user['member']->assignRole('member');
    }

    public function createUser()
    {
        $result['admin'] = User::create([
            'name' => $name = 'Admin',
            'shop_name' => $name,
            'username' => 'admin123',
            'phone' => '62895347113987',
            'address' => 'Perum Bumi Cengkong Lestari Blok B5 No.12 Rt 11/12, Cengkong, Purwasari, Karawang',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $result['reseller'] = User::create([
            'name' => $name = 'Reseller',
            'shop_name' => $name,
            'username' => 'reseller',
            'phone' => '6289534711398',
            'address' => 'Perum Bumi Cengkong Lestari Blok B5 No.12 Rt 11/12, Cengkong, Purwasari, Karawang',
            'email' => 'reseller@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $result['agen'] = User::create([
            'name' => $name = 'agen',
            'shop_name' => $name,
            'username' => 'agen123',
            'phone' => '628953471139',
            'address' => 'Perum Bumi Cengkong Lestari Blok B5 No.12 Rt 11/12, Cengkong, Purwasari, Karawang',
            'email' => 'agen@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        $result['member'] = User::create([
            'name' => $name = 'member',
            'shop_name' => $name,
            'username' => 'member',
            'phone' => '62895347113',
            'address' => 'Perum Bumi Cengkong Lestari Blok B5 No.12 Rt 11/12, Cengkong, Purwasari, Karawang',
            'email' => 'member@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        return $result;
    }
}
