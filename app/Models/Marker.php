<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Marker extends Model
{
use HasFactory;

    protected $fillable = [
        'unique_code',
        'video_path',
        'photobooth_id',
        'description',
    ];
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->unique_code)) {
                $model->unique_code = self::generateUniqueCode();
            }
        });
    }
    public static function generateUniqueCode()
    {
        $code = Str::upper(Str::random(8));
        while (self::where('unique_code', $code)->exists()) {
            $code = Str::upper(Str::random(8));
        }
        return $code;
    }
    public function photos(): HasMany
    {
        return $this->hasMany(Photo::class)->orderBy('order');
    }
    public function photobooth()
    {
        return $this->belongsTo(Photobooth::class);
    }
}
