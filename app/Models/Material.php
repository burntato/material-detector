<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $primaryKey = 'mat_id';
    protected $fillable = ['mat_name'];

    // without timestamps
    public $timestamps = false;

    public function images() {
        return $this->hasMany(Image::class, 'mat_id');
    }
    
}
