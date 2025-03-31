<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;
    protected $fillable = ['name','email','phone','department_id'];

    public function department() {
      return $this->hasOne('App\Models\Department', 'id', 'department_id');
    }

}
