<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photobooth extends Model
{
    use HasFactory;
    protected $table = 'photobooths';
    protected $fillable = ['nama', 'alamat'];
    public function markers()
    {
        return $this->hasMany(Marker::class);
    }
}
