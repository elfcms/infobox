<?php

namespace Elfcms\Infobox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoboxOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'data_type_id',
        'value',
    ];

    public function items()
    {
        return $this->belongsToMany(InfoboxOption::class, 'infobox_item_options', 'item_id', 'option_id');
    }
}
