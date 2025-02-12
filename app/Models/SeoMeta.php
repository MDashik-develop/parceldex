<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoMeta extends Model
{
    use HasFactory;

    protected $fillable = ['og_title', 'og_description', 'og_image', 'og_url', 'meta_keywords', 'canonical_url'];
}
