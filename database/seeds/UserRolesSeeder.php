<?php

use Illuminate\Database\Seeder;
use App\Role;
use App\Permission;

class UserRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = '管理員'; // optional
        $admin->description  = '最高權限'; // optional
        $admin->save();

        $agent = new Role();
        $agent->name         = 'agent';
        $agent->display_name = '經銷商'; // optional
        $agent->description  = '可開客戶'; // optional
        $agent->save();

        $client = new Role();
        $client->name         = 'client';
        $client->display_name = '客戶'; // optional
        $client->description  = '可開部門'; // optional
        $client->save();

        $department = new Role();
        $department->name         = 'department';
        $department->display_name = '客戶'; // optional
        $department->description  = '最低權限，配一台機器'; // optional
        $department->save();


        $createAgent = new Permission();
        $createAgent->name         = 'create-agent';
        $createAgent->display_name = 'Create Agent';
        $createAgent->description  = 'create new agent';
        $createAgent->save();

        $createClient = new Permission();
        $createClient->name         = 'create-client';
        $createClient->display_name = 'Create Client';
        $createClient->description  = 'create new client';
        $createClient->save();

        $createDepartment = new Permission();
        $createDepartment->name         = 'create-department';
        $createDepartment->display_name = 'Create Department';
        $createDepartment->description  = 'create new department';
        $createDepartment->save();

        $createDevice = new Permission();
        $createDevice->name         = 'create-device';
        $createDevice->display_name = 'Create Device';
        $createDevice->description  = 'create new device';
        $createDevice->save();

        $admin->attachPermission($createAgent);
        $agent->attachPermission($createClient);
        $client->attachPermission($createDepartment);
        $department->attachPermission($createDevice);

        // $owner->attachPermissions(array($createPost, $editUser));
    }
}
