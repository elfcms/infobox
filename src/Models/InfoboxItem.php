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
        'category_id',
        'position',
        'infobox_id',
        'description',
        'meta_keywords',
        'meta_description',
        'active',
        'public_time',
        'end_time',
    ];


    protected $appends = ['props'];

    public function getPropsAttribute()
    {
        return $this->props();
    }

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

    public function scopePosition($query)
    {
        return $query->orderBy('position');
    }

    public function category ()
    {
        return $this->belongsTo(InfoboxCategory::class, 'category_id');
    }

    public function infobox ()
    {
        return $this->belongsTo(Infobox::class, 'infobox_id');
    }

    /* public function properties()
    {
        return $this->hasMany(InfoboxItemProperty::class, 'item_id');
    } */

    public function properties()
    {
        return $this->hasMany(InfoboxItemPropertyValue::class, 'item_id');
    }

    public function props()
    {
        $result = [];
        $props = $this->hasMany(InfoboxItemPropertyValue::class, 'item_id')->get();
        foreach ($props as $prop) {
            $name = $prop->property->code;
            $value = $prop->{$prop->property->data_type->code.'_value'};
            $result[$name] = $value;
        }
        return $result;
    }

    public function data()
    {
        return array_merge($this->toArray(),$this->props());
    }
}
