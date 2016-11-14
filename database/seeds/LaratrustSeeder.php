<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaratrustSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return  void
     */
    public function run()
    {
        $this->command->info('Truncating User, Role and Permission tables');
        $this->truncateLaratrustTables();
        
        // generate the roles
        $superman_role = \App\Role::create([
            'name' => 'superman',
            'display_name' => 'Superman',
            'description' => 'Can do anything. Does\'t even wait.'
        ]);

        $administrator_role = \App\Role::create([
            'name' => 'administrator',
            'display_name' => 'Administrator',
            'description' => 'Edit and delete privileges.'
        ]);

        $moderator_role = \App\Role::create([
            'name' => 'moderator',
            'display_name' => 'Moderator',
            'description' => 'Can approve posts.'
        ]);

        $user_role = \App\Role::create([
            'name' => 'user',
            'display_name' => 'User',
            'description' => 'Plebs.'
        ]);

        // generate the permissions
        $access_admin_permission = \App\Permission::firstOrCreate([
            'name' => 'access-admin',
            'display_name' => 'Access Admin',
            'description' => 'Can access the admin panel',
        ]);

        $edit_users = \App\Permission::firstOrCreate([
            'name' => 'edit-users',
            'display_name' => 'Edit Users',
            'description' => 'Can edit users.',
        ]);

        $delete_users = \App\Permission::firstOrCreate([
            'name' => 'delete-users',
            'display_name' => 'Delete Users',
            'description' => 'Can delete users.',
        ]);

        $edit_incidents = \App\Permission::firstOrCreate([
            'name' => 'edit-incidents',
            'display_name' => 'Edit Incidents',
            'description' => 'Can edit incidents.',
        ]);

        $delete_incidents = \App\Permission::firstOrCreate([
            'name' => 'delete-incidents',
            'display_name' => 'Delete Incidents',
            'description' => 'Can delete incidents.',
        ]);

        $moderate_incidents = \App\Permission::firstOrCreate([
            'name' => 'moderate-incidents',
            'display_name' => 'Moderate Incidents',
            'description' => 'Can moderate incidents.',
        ]);


        // attach the permissions to the roles
        $superman_role->attachPermission($access_admin_permission);
        $administrator_role->attachPermission($access_admin_permission);
        $moderator_role->attachPermission($access_admin_permission);

        $superman_role->attachPermission($edit_users);

        $superman_role->attachPermission($delete_users);

        $superman_role->attachPermission($edit_incidents);
        $administrator_role->attachPermission($edit_incidents);

        $superman_role->attachPermission($delete_incidents);
        $administrator_role->attachPermission($delete_incidents);

        $superman_role->attachPermission($moderate_incidents);
        $administrator_role->attachPermission($moderate_incidents);
        $moderator_role->attachPermission($moderate_incidents);

        // generate the users

        $admin_user = \App\User::create([
            'name' => 'Erik Westlund',
            'email' => 'edbwestlund@gmail.com',
            'password' => bcrypt('kryptonite'),
            'remember_token' => str_random(10),
        ]);

        // attach the roles to the users
        $admin_user->attachRole($superman_role);
    }

    /**
     * Truncates all the laratrust tables and the users table
     * @return    void
     */
    public function truncateLaratrustTables()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        DB::table('permission_role')->truncate();
        DB::table('role_user')->truncate();
        \App\User::truncate();
        \App\Role::truncate();
        \App\Permission::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
