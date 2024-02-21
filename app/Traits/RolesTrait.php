<?php

namespace App\Traits;

use Spatie\Permission\Models\Permission;

trait RolesTrait
{
    public function splitArrayWithComma($permissions)
    {
        $i = 0;
        $roles = null;
        while($i < count($permissions))
        {
            $permisos = Permission::select('description')->Where('id', $permissions[$i])->first();
            $roles .= $permisos['description']; 
            $u = $i + 1;
            if($u < count($permissions)) {
                $roles .= ', ';
            }
            $i++;
        };
        return $roles;
    }
}

?>