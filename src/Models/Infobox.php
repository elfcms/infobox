<?php

namespace Elfcms\Infobox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Infobox extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'meta_keywords',
        'meta_description',
        'active'
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

    public function items ()
    {
        return $this->hasMany(InfoboxItem::class, 'infobox_id');
    }

    public function topItems ()
    {
        return $this->hasMany(InfoboxItem::class, 'infobox_id')->where('category_id',null);
    }

    public function categories ()
    {
        return $this->hasMany(InfoboxCategory::class, 'infobox_id');
    }

    public function topCategories ()
    {
        return $this->hasMany(InfoboxCategory::class, 'infobox_id')->where('parent_id',null);
    }

    public function categoryProperties ()
    {
        return $this->hasMany(InfoboxCategoryProperty::class, 'infobox_id');
    }

    public function itemProperties ()
    {
        return $this->hasMany(InfoboxItemProperty::class, 'infobox_id');
    }

}
