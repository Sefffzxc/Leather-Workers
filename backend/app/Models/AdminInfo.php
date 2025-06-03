<?php

namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AdminInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'name',
        'image',
        'status',
        'skills',
        'skill_level',
        'signature_products'
    ];

    protected $casts = [
        'skills' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Accessor for image URL
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::disk('public')->url($this->image);
        }
        
        // Return default avatar if no image
        return asset('images/default-avatar.png');
    }

    // Check if admin has image
    public function hasImage()
    {
        return !empty($this->image) && Storage::disk('public')->exists($this->image);
    }
}