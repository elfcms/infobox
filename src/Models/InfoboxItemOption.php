<?php

namespace Elfcms\Infobox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoboxItemOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id',
        'data_type_id',
        'name',
        'value',
        'value_int',
        'value_float',
        'value_date',
        'value_datetime',
    ];

    //protected $table = 'infobox_item_options';

    public function items()
    {
        return $this->belongsTo(InfoboxItem::class, 'item_id');
    }

    public function datatypes()
    {
        return $this->belongsTo(InfoboxDataType::class, 'data_type_id', 'id', 'data_types');
    }

    public function getValueAttribute($value)
    {
        $typeCode = InfoboxDataType::find($this->data_type_id);
        switch ($typeCode->code) {
            case 'int':
                $value = $this->value_int;
                break;

            case 'float':
                $value = $this->value_float;
                break;

            case 'date':
                $value = $this->value_date;
                break;

            case 'datetime':
                $value = $this->value_datetime;
                break;

            default:
                //
                break;
        }
        return $value;
    }
}
