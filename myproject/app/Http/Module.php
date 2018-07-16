<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $table = 'managemodules';
    protected $fillable = [
      'module_name',
      'update_by',
      'create_by',

    ];
}
