<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'created_by',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Optional default attribute values
    protected $attributes = [
        'status' => 'draft',
    ];

    public function scopePublished(Builder $q): Builder
    {
        return $q->where('status', 'published');
    }

    public function scopeDraft(Builder $q): Builder
    {
        return $q->where('status', 'draft');
    }

    /**
     * Pages that should appear in the public navbar.
     * Can later add ordering, exclusions, etc. here.
     */
    public function scopeNav(Builder $q): Builder
    {
        return $q->published()->orderBy('title');
    }

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }
}
