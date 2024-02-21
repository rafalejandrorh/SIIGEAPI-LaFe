<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users_Questions extends Model
{
    use HasFactory;

    protected $table = 'users_questions';

    protected $fillable = ['id_users','id_questions', 'response', 'id_padre'];

}
