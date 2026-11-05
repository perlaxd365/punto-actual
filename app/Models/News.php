<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    protected $fillable = ['category_id','user_id','title','slug','excerpt','body','image','city','country','source_name','source_url','status','is_featured','in_cover','cover_at','published_at','seo_title','seo_description'];
    protected $casts = ['published_at'=>'datetime','is_featured'=>'boolean','in_cover'=>'boolean','cover_at'=>'datetime'];
    public function scopePublished(Builder $q): Builder { return $q->where('status','published')->whereNotNull('published_at')->where('published_at','<=',now()); }
    public function scopeFeatured(Builder $q): Builder { return $q->where('is_featured',true); }
    public function scopeCover(Builder $q): Builder { return $q->where('in_cover',true)->orderByDesc('cover_at')->orderByDesc('published_at'); }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function author(): BelongsTo { return $this->belongsTo(User::class,'user_id'); }
    public function getPlaceLabelAttribute(): ?string {
        if ($this->category?->slug === 'internacional') { return $this->country ?: 'Internacional'; }
        return $this->city;
    }
}
