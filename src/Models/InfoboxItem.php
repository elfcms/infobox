<?php

namespace Elfcms\Infobox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoboxItem extends Model
{
    use HasFactory;



    protected $fillable = [
        'title',
        'slug',
        'parent_id',
        'infobox_id',
        'description',
        'meta_keywords',
        'meta_description',
        'active',
        'public_time',
        'end_time',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeActive($query)
    {
        return $query->where('active',1);
    }

    public function category ()
    {
        return $this->belongsTo(InfoboxCategory::class, 'category_id');
    }

    public function properties()
    {
        return $this->hasMany(InfoboxItemProperty::class, 'item_id');
    }
}
