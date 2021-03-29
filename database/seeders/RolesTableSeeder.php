<?php

namespace Database\Seeders;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $developer= new Role();
        $developer->name ='developer';
        $developer->display_name='Developer';
        $developer->description='developer of the application';
        $developer->save();

        $developer= new Role();
        $developer->name ='user';
        $developer->display_name='user';
        $developer->description='user of the application';
        $developer->save();

        $developer= new Role();
        $developer->name ='admin';
        $developer->display_name='admin';
        $developer->description='admin of the application';
        $developer->save();

        $developer= new Role();
        $developer->name ='agent';
        $developer->display_name='agent';
        $developer->description='agent of the application';
        $developer->save();

    }
}
