<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sections extends Model
{
    use HasFactory;
    protected $fillable = [
        'description', 'created_by','section_name'
    ];
    //Relationship With Products
    public function product(){
        return $this->hasMany(Products::class, 'section_id');
    }
}