<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $users = factory(App\User::class, 20)->create();

         foreach($users as $user) {
            $rand = rand(1,4);
            
            $user->roles()->detach();

            if($rand == 1){
                $user->attachRole(App\Role::where('name', 'superman')->first());
            } else if($rand == 2){
                $user->attachRole(App\Role::where('name', 'administrator')->first());
            } else if($rand == 3){
                $user->attachRole(App\Role::where('name', 'moderator')->first());
            } else if($rand == 4){
                $user->attachRole(App\Role::where('name', 'user')->first());
            } 
            
         }
    }
}
