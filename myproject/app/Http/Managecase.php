<?php

namespace App\Http;

use Illuminate\Database\Eloquent\Model;

class Managecase extends Model
{
    protected $table = 'managecases';
    protected $fillable = [
      'case_name',
      'case_code',
      'user_id',
      'project_id',
      'module_id',
      'function_id',
      'updated_at',
      'plan_id',
      'status_code'
    ];
}
