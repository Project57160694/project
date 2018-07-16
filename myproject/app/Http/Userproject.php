<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userproject extends Model
{
    protected $table = 'userprojects';
    protected $fillable = [
      'user_id',
      'project_id',
      'role_id',

    ];
}
