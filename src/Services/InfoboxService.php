<?php

namespace Elfcms\Infobox\Services;

use Elfcms\Infobox\Models\Infobox;

class InfoboxService
{
    /**
     *
     * @param  mixed  $input  Infobox|int|string (slug)
     * @return object|null
     */
    public function getGrouped($input)
    {
        // basic query + eager loading
        $query = Infobox::with([
            'categoryProperties',
            'itemProperties',
            'categories' => function ($q) {
                $q->with([
                    'propertyValues.property',               
                    'items.propertyValues.property'          
                ]);
            },
            'items' => function ($q) {
                $q->whereNull('category_id')
                    ->with('propertyValues.property');
            }
        ]);

        if ($input instanceof Infobox) {
            $infobox = $query->find($input->id);
        } elseif (is_numeric($input)) {
            $infobox = $query->find($input);
        } else {
            $infobox = $query->where('slug', $input)->first();
        }

        if (! $infobox) {
            return null;
        }

        $result = (object) [
            'id' => $infobox->id,
            'title' => $infobox->title,
            'slug' => $infobox->slug,
            'description' => $infobox->description,
            'active' => (bool) $infobox->active,
            'created_at' => $infobox->created_at,
            'updated_at' => $infobox->updated_at,
            'category_properties' => $infobox->categoryProperties,
            'item_properties'     => $infobox->itemProperties,
            'items_without_category' => $infobox->items->map(function ($item) {
                return $this->formatItem($item);
            }),
            'categories' => $infobox->categories->map(function ($cat) {
                return (object) [
                    'id' => $cat->id,
                    'title' => $cat->title,
                    'slug' => $cat->slug,
                    'description' => $cat->description,
                    'active' => (bool) $cat->active,
                    'properties' => $cat->propertyValues->map(function ($pv) {
                        return (object) [
                            'property' => $pv->property,
                            'value' => $this->valueFromPropertyValue($pv)
                        ];
                    }),
                    'items' => $cat->items->map(function ($item) {
                        return $this->formatItem($item);
                    })
                ];
            })
        ];

        return $result;
    }

    public function getGroupedModels($input)
    {
        // basic query + eager loading
        $query = Infobox::with([
            'categoryProperties',
            'itemProperties',
            'categories' => function ($q) {
                $q->with([
                    'propertyValues.property',               
                    'items.propertyValues.property'          
                ]);
            },
            'items' => function ($q) {
                $q->whereNull('category_id')
                    ->with('propertyValues.property');
            }
        ]);

        if ($input instanceof Infobox) {
            $infobox = $query->find($input->id);
        } elseif (is_numeric($input)) {
            $infobox = $query->find($input);
        } else {
            $infobox = $query->where('slug', $input)->first();
        }

        if (! $infobox) {
            return null;
        }

        $result = (object) [
            'id' => $infobox->id,
            'title' => $infobox->title,
            'slug' => $infobox->slug,
            'description' => $infobox->description,
            'active' => (bool) $infobox->active,
            'created_at' => $infobox->created_at,
            'updated_at' => $infobox->updated_at,
            'category_properties' => $infobox->categoryProperties,
            'item_properties'     => $infobox->itemProperties,
            'items_without_category' => $infobox->items->map(function ($item) {
                return $this->formatItem($item);
            }),
            'categories' => $infobox->categories
        ];

        return $result;
    }

    protected function formatItem($item)
    {
        return (object) [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'description' => $item->description,
            'active' => (bool) $item->active,
            'properties' => $item->propertyValues->map(function ($pv) {
                return (object) [
                    'property' => $pv->property,
                    'value' => $this->valueFromPropertyValue($pv)
                ];
            })
        ];
    }
    
    protected function valueFromPropertyValue($pv)
    {
        foreach (
            [
                'bool_value',
                'int_value',
                'float_value',
                'string_value',
                'text_value',
                'date_value',
                'datetime_value',
                'file_value',
                'image_value'
            ] as $field
        ) {
            if (! is_null($pv->$field)) {
                return $pv->$field;
            }
        }
        return null;
    }
}
