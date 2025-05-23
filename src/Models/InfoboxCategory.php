<?php

namespace Elfcms\Infobox\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InfoboxCategory extends Model
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
        'active'
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
        return $query->where('active', 1);
    }

    public function scopePosition($query)
    {
        return $query->orderBy('position');
    }

    public function infobox()
    {
        return $this->belongsTo(Infobox::class, 'infobox_id');
    }

    public function items()
    {
        return $this->hasMany(InfoboxItem::class, 'category_id');
    }

    /* public function properties()
    {
        return $this->hasMany(InfoboxCategoryProperty::class, 'category_id');
    } */

    public function properties()
    {
        return $this->hasMany(InfoboxCategoryPropertyValue::class, 'category_id');
    }

    public function props()
    {
        $result = [];
        $props = $this->hasMany(InfoboxCategoryPropertyValue::class, 'category_id')->get();
        foreach ($props as $prop) {
            $name = $prop->property->code;
            $value = $prop->{$prop->property->data_type->code . '_value'};
            $result[$name] = $value;
        }
        return $result;
    }

    public function data()
    {
        return array_merge($this->toArray(), $this->props());
    }

    public static function tree($parent = null)
    {
        if ($parent !== null) {
            $parent = intval($parent);
            if ($parent == 0) {
                $parent = null;
            }
        }
        $result = [];
        $result = self::where('parent_id', $parent)->get();
        if (!empty($result)) {
            foreach ($result as $i => $item) {
                $sublevelData = self::tree($item->id);
                if (!empty($sublevelData)) {
                    $result[$i]['children'] = $sublevelData;
                }
            }
        }

        return $result;
    }

    public function subtree($parent = null)
    {
        static $level = 0;



        /* if ($parent !== null) {
            $parent = intval($parent);
            if ($parent == 0) {
                $parent = null;
            }
        }
        $result = [];
        $result = $this->where('parent_id',$parent)->get();
        if (!empty($result)) {
            foreach ($result as $i => $item) {
                $sublevelData = $this->subtree($item->id);
                if (!empty($sublevelData)) {
                    $result[$i]['children'] = $sublevelData;
                }
            }
        } */

        return $this->get();
    }

    public function categories()
    {
        return $this->hasMany(InfoboxCategory::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(InfoboxCategory::class, 'parent_id');
    }

    public function parentsId($current = null)
    {
        static $ids = [];

        /* if (!empty($this->parent)) {
            $ids[] = $this->parent->id;
            $this->parentsId();
        } */

        if (empty($current) && !empty($this->parent)) {
            $ids[] = $this->parent->id;
            $this->parentsId($this->parent);
        } elseif (!empty($current->parent)) {
            $ids[] = $current->parent->id;
            $this->parentsId($current->parent);
        }

        return $ids;
    }

    public static function flat($parent = null, $level = 0, $trend = 'asc', $order = 'id', $count = 100, $search = '')
    {
        if (!empty($trend) && $trend == 'desc') {
            $trend = 'desc';
        }
        if (!empty($order)) {
            $order = $order;
        }
        if (!empty($count)) {
            $count = intval($count);
        }
        if (empty($count)) {
            $count = 100;
        }
        if ($parent !== null) {
            $parent = intval($parent);
            if ($parent == 0) {
                $parent = null;
            }
        }
        $result = [];
        if (!empty($search)) { //only for 0 level
            $data = self::where('parent_id', $parent)->where('name', 'like', "%{$search}%")->orderBy($order, $trend)->paginate($count);
        } else {
            $data = self::where('parent_id', $parent)->orderBy($order, $trend)->paginate($count);
        }

        if (!empty($data)) {
            foreach ($data as $item) {
                $item['level'] = $level;
                $result[] = $item;
                $sublevelData = self::flat(parent: $item->id, level: $level + 1);
                if (!empty($sublevelData)) {
                    $result = array_merge($result, $sublevelData);
                }
            }
        }

        return $result;
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public static function childrenRecursive($id, $subchild = false)
    {
        $result = [];
        $data = self::where('parent_id', $id)->get();

        foreach ($data as $item) {
            $result[] = $item;
            if ($subchild) {
                $subresult = self::childrenRecursive($item->id, $subchild);
                if (!empty($subresult)) {
                    $result = array_merge($result, $subresult);
                }
            }
        }

        return $result;
    }

    public static function childrenid($id, $subchild = false)
    {
        $result = [];
        $data = self::where('parent_id', $id)->get('id');

        foreach ($data as $item) {
            $result[] = $item->id;
            if ($subchild) {
                $subresult = self::childrenid($item->id, $subchild);
                if (!empty($subresult)) {
                    $result = array_merge($result, $subresult);
                }
            }
        }

        return $result;
    }
}
