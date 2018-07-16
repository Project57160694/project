<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Managefunction extends Model
{
    protected $table = 'managefunctions';
    protected $fillable = [
      'module_id',
      'project_id',
      'function_name',
      'short_name',
      'status_code',
      'update_at',
      'create_at',

    ];
}
