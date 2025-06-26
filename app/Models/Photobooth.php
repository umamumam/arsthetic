<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Photobooth extends Model
{
    use HasFactory;
    protected $table = 'photobooths';
    protected $fillable = ['nama', 'alamat', 'user_id'];
    public function markers()
    {
        return $this->hasMany(Marker::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
