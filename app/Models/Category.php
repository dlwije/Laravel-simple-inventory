<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['c_name','parent_id','is_active'];

    public function children()
    {
        return $this->hasMany('App\Models\Category', 'parent_id');
    }
}
