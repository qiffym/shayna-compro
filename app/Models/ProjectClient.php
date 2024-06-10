<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectClient extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function testimonials(): HasMany
    {
        return $this->hasMany(Testimonial::class);
    }
}
