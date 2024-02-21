<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Spatie\Permission\Models\Role;

class RolesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $roles = Role::select('name', 'created_at')->get();

        $i = count($roles) - 1;
        while($i >= 0)
        {
            $e = $i + 1;
            $roles[$e] = $roles[$i];
            $i--;
        }

        $roles[0] = [
            'Rol', 
            'Creado' 
        ];

        return $roles;
    }
}
