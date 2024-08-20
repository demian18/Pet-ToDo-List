<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = ['id', 'name', 'email', 'password', 'username', 'role_id', 'picture', 'period', ];
    public $timestamps = false;
}
