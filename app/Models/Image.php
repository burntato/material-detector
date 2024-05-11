<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $primaryKey = 'img_id';
    protected $fillable = ['img_name'];

    // without timestamps
    public $timestamps = false;

    public function material() {
        return $this->belongsTo(Material::class, 'mat_id');
    }
}
