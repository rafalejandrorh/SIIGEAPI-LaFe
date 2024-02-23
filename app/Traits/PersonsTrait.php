<?php

namespace App\Traits;

use App\Models\Person;
use Illuminate\Support\Facades\Cache;

trait PersonsTrait
{
    public function getPersonById($id = 0)
    {
        if(Cache::has('person-'.$id)) {
            return Cache::get('person-'.$id);
        }else{
            $result = Person::Where('id', $id)->first();
            Cache::put('person-'.$id, $result, 1800);
            return $result;
        }
    }

    public function pluckPerson()
    {
        return Person::orderBy('persons.primer_nombre', 'asc')->get();
    }
}

?>