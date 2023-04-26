<?php

namespace Elfcms\Infobox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoboxItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'image',
        'text',
    ];

    public function options()
    {
        return $this->hasMany(InfoboxItemOption::class, 'item_id');
    }
}
