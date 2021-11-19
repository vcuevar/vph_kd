<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
class TestController extends Controller
{
    public function test()
    {
        // Añadir personaje
        $role = Role::create(['name' => 'empleado']);
        //Role::create(['name' => 'alumno']);
        $role->givePermissionTo('role-list');

        $user = User::find(1);
        $user->assignRole('empleado');
    }
}