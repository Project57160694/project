<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    protected $table = 'status';
    protected $fillable = [
      'stau_name',
      'code',
      'type',
      'major_code',

    ];
}
