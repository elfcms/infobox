<?php

namespace Elfcms\Infobox\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoboxCategoryPropertyValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'category_id',
        'bool_value',
        'int_value',
        'float_value',
        'string_value',
        'text_value',
        'date_value',
        'datetime_value',
        'file_value',
        'image_value',
        'list_value',
        'json_value',
        'time_value',
        'color_value',
    ];

    public function property()
    {
        return $this->belongsTo(InfoboxCategoryProperty::class, 'property_id');
    }

    public function item()
    {
        return $this->belongsTo(InfoboxCategory::class, 'category_id');
    }

    public function getValueAttribute()
    {
        $field = $this->property->data_type->code . '_value';
        return $this->{$field};
    }

    public function getCodeAttribute()
    {
        return $this->property->code;
    }

    public function getNameAttribute()
    {
        return $this->property->name;
    }

    public function getTypeCodeAttribute()
    {
        return $this->property->data_type->code;
    }

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function listValue(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
        );
    }
}
