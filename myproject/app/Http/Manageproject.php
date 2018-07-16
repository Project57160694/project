<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manageproject extends Model
{
    protected $table = 'manageprojects';
    protected $fillable = [
      'project_name',
      'status_code',
      'update_by',
      'create_by',

    ];
}
