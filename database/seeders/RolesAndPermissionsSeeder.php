<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $collection = collect([
            'User',
            'Role',
            'Permission',
            'Landlord',
            // ... // List all your Models you want to have Permissions for.
        ]);

        $collection->each(function ($item, $key) {
            // create permissions for each collection item
            Permission::create(['group' => $item, 'name' => 'viewAny'.$item]);
            Permission::create(['group' => $item, 'name' => 'view'.$item]);
            Permission::create(['group' => $item, 'name' => 'update'.$item]);
            Permission::create(['group' => $item, 'name' => 'create'.$item]);
            Permission::create(['group' => $item, 'name' => 'delete'.$item]);
            Permission::create(['group' => $item, 'name' => 'destroy'.$item]);
        });

        // Create a Super-Admin Role and assign all Permissions
        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(Permission::all());

        // Give User Super-Admin Role
        $user = \App\Models\User::where('email', 'mahmoud@birdsol.com')->first(); // Change this to your email.
        $user->assignRole('super-admin');
    }
}
