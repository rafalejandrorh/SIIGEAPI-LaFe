<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role1 = Role::create(['name' => 'Superadmin']);

        Permission::create(['name' => 'users.index', 'description' => 'Ver listado de Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.create', 'description' => 'Crear Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.edit', 'description' => 'Editar Usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.show', 'description' => 'Ver usuarios'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.password', 'description' => 'Cambiar Contraseña de Usuario Propio'])->syncRoles([$role1]);
        Permission::create(['name' => 'users.update_status', 'description' => 'Modificar Estatus de Usuarios'])->syncRoles([$role1]);

        Permission::create(['name' => 'roles.index', 'description' => 'Ver listado de Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'roles.edit', 'description' => 'Editar Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'roles.show', 'description' => 'Ver Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'roles.create', 'description' => 'Crear Roles'])->syncRoles([$role1]);
        Permission::create(['name' => 'roles.destroy', 'description' => 'Eliminar Roles'])->syncRoles([$role1]);

        Permission::create(['name' => 'trazas.index', 'description' => 'Ver listado de Trazas'])->syncRoles([$role1]);

        Permission::create(['name' => 'sessions.index', 'description' => 'Ver listado de Sesiones'])->syncRoles([$role1]);
        Permission::create(['name' => 'sessions.destroy', 'description' => 'Eliminar Sesiones'])->syncRoles([$role1]);

        Permission::create(['name' => 'logs.index', 'description' => 'Ver Logs'])->syncRoles([$role1]);
    }
}
