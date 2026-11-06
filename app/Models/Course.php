<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'entity_id', 'title', 'slug', 'area', 'modality', 'level', 'duration', 'excerpt',
        'description', 'image', 'official_url', 'status', 'is_featured', 'published_at',
        'seo_title', 'seo_description',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published')
            ->where(function (Builder $builder) {
                $builder->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function getProviderLabelAttribute(): string
    {
        return $this->entity?->name ?? 'Institución por confirmar';
    }
}
