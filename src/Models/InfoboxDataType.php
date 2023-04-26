<?php

namespace Elfcms\Infobox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoboxDataType extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'lang_name',
    ];

    protected $strings = [
        ['code' => 'text', 'name' => 'Text', 'lang_name' => 'text'],
        ['code' => 'string', 'name' => 'String', 'lang_name' => 'string'],
        ['code' => 'int', 'name' => 'Integer', 'lang_name' => 'integer'],
        ['code' => 'float', 'name' => 'Float', 'lang_name' => 'float'],
        ['code' => 'date', 'name' => 'Date', 'lang_name' => 'date'],
        ['code' => 'datetime', 'name' => 'Datetime', 'lang_name' => 'datetime'],
        ['code' => 'file', 'name' => 'file', 'lang_name' => 'file'],
        ['code' => 'image', 'name' => 'image', 'lang_name' => 'image'],
    ];

    public function start()
    {
        foreach($this->strings as $string) {
            $exists = $this->where('name',$string['name'])->count();
            if ($exists && $exists > 0) {
                continue;
            }

            $newString = $this->create($string);

            if (!$newString) {
                return false;
            }
        }
    }
}
