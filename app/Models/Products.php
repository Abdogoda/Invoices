<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable =[
        'section_id','product_name','description'
    ];
    public function section(){
        return $this->belongsTo(sections::class, 'section_id');
    }
}