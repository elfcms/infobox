<?php

namespace Elfcms\Infobox\Models;

use Elfcms\Basic\Models\DataType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoboxCategoryProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'infobox_id',
        'data_type_id',
        'name',
        'code',
        'description',
        'multiple',
        'options',
    ];

    public $value, $colorData;

    public function data_type()
    {
        return $this->belongsTo(DataType::class, 'data_type_id');
    }

    public function setValueAttribute()
    {
        return null;
    }

    public function values(int|null $categoryId = null)
    {
        $result = $this->hasMany(InfoboxCategoryPropertyValue::class, 'property_id');
        if (!empty($categoryId) && is_numeric($categoryId)) {
            $result = $result->where('category_id', $categoryId)->get()->toArray();
            if (!empty($result)) {
                $result = $result[0][$this->data_type->code . '_value'];
            }
            else {
                $result = null;
            }
        }
        return $result;
    }

    /**
     * Get the user's first name.
     *
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    protected function options(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => json_decode($value, true),
        );
    }

    public function infobox()
    {
        return $this->belongsTo(Infobox::class, 'infobox_id');
    }
}
