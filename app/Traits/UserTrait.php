<?php

namespace App\Traits;

use Spatie\Permission\Models\Role;

trait UserTrait
{
    public function splitArrayWithComma($roles)
    {
        $i = 0;
        $role = null;
        while($i < count($roles)) {
            if(isset($roles[$i]['name'])) {
                $role .= $roles[$i]['name'];
            }else{
                $rol = Role::Where('id', $roles[$i])->first();
                $role .= $rol['name'];
            }

            $u = $i + 1;
            if($u < count($roles)) {
                $role .= ', ';
            }
            $i++;
        }
        return $role;
    }
    
}

?>