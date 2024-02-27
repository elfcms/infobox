<?php

namespace Elfcms\Infobox\Http\Controllers;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\DataType;
use Elfcms\Infobox\Models\InfoboxCategoryProperty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InfoboxCategoryPropertyController extends Controller
{

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request, $byId = false)
    {
        $dataTypes = DataType::all();
        $properties = InfoboxCategoryProperty::all();
        if ($request->ajax()) {
            $propertyData = $properties->toArray();
            if ($byId) {
                $newData = [];
                foreach ($propertyData as $property) {
                    $newData[$property['id']] = $property;
                }
                $propertyData = $newData;
                unset($newData);
            }
            return [
                'result' => 'success',
                'message' => '',
                'data' => $propertyData
            ];
        }
        else {
            return view(
                'elfcms::admin.infobox.properties.content.list',
                [
                    'properties' => $properties,
                    'data_types' => $dataTypes,
                ]
            );
        }
    }

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function emptyItem(Request  $request)
    {
        if (!$request->ajax()) {
            abort(404);
        }
        $dataTypes = DataType::all();
        $emptyUnit = [
            'id' => 'newproperty',
            'data_type_id' => null,
            'code' => null,
            'name' => null,
            'description' => null,
            'multiple' => 0,
            'data_type' => (object)['code'=>null],
        ];
        return view(
            'elfcms::admin.infobox.properties.content.item',
            [
                'property' => (object)$emptyUnit,
                'data_types' => $dataTypes,
            ]
        );
    }

    /**
     *
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request  $request)
    {
        $result = [
            'result' => 'error',
            'message' => '',
            'data' => null
        ];
        if (!$request->isMethod('POST')) {
            $result['message'] = __('infobox::default.method_must_be',['method'=>'POST']);
            return $result;
        }

        $except = [];
        $propertyDelete = [];

        if (!empty($request->property)) {
            foreach($request->property as $id => $property) {
                if (!empty($property['delete'])) {
                    $propertyDelete[] = $id;
                    $except[] = 'property.'.$id;
                }
            }
        }

        if (!empty($request->newproperty)) {
            foreach($request->newproperty as $id => $newproperty) {
                if (!empty($newproperty['delete']) || (empty($newproperty['name']) && empty($newproperty['abbr']))) {
                    $except[] = 'newproperty.'.$id;
                }
            }
        }
        $data = $request->except($except);

        $validator = Validator::make($data, [
            '*property.*.name' => 'required',
            '*property.*.code' => 'required',
            '*property.*.data_type_id' => 'integer|required',
        ]);

        if ($validator->fails()) {
            $errorsData = $validator->errors()->messages();
            $errorsMessages = [];
            foreach ($errorsData as $message) {
                $errorsMessages[] = $message[0];
            }
            $result['message'] = implode('. ', $errorsMessages);

            return $result;
        }

        if (!empty($propertyDelete)) {
            $deleted = InfoboxCategoryProperty::destroy($propertyDelete);
            if (!$deleted) {
                $result['message'] = __('infobox::default.error_of_deleting');

                return $result;
            }
        }

        if (!empty($data['property'])) {
            foreach ($data['property'] as $id => $property) {
                $propertyItem = InfoboxCategoryProperty::find($id);
                if ($propertyItem) {
                    $options = [];
                    if (!empty($property['options']) && is_array($property['options'])) {
                        foreach ($property['options'] as $option) {
                            if (!empty($option['delete']) || (empty($option['key']) && empty($option['value']))) {
                                continue;
                            }
                            $options[$option['key']] = $option['value'];
                        }
                    }
                    $propertyItem->infobox_id = $request->infobox_id;
                    $propertyItem->name = $property['name'];
                    $propertyItem->code = $property['code'];
                    $propertyItem->data_type_id = $property['data_type_id'];
                    $propertyItem->description = $property['description'];
                    $propertyItem->multiple = empty($parameter['multiple']) ? 0 : 1;
                    $propertyItem->options = json_encode($options);
                    $saved = $propertyItem->save();
                    if (!$saved) {
                        $result['message'] =  __('infobox::default.error_of_saving_id',['id'=>$id]);

                        return $result;
                    }
                }
            }
        }

        if (!empty($data['newproperty'])) {
            foreach ($data['newproperty'] as $newproperty) {
                $newproperty['infobox_id'] = $request->infobox_id;
                if (empty($newproperty['multiple'])) {
                    $newproperty['multiple'] = 0;
                }
                else {
                    $newproperty['multiple'] = 1;
                }
                $options = [];
                if (!empty($newproperty['options']) && is_array($newproperty['options'])) {
                    foreach ($newproperty['options'] as $option) {
                        if (!empty($option['delete']) || (empty($option['key']) && empty($option['value']))) {
                            continue;
                        }
                        $options[$option['key']] = $option['value'];
                    }
                    $newproperty['options'] = json_encode($options);
                }
                $created = InfoboxCategoryProperty::create($newproperty);
                if (!$created) {
                    $result['message'] = __('infobox::default.error_of_creating_element_with_name',['name'=>$newproperty['name']]);

                    return $result;
                }
            }
        }

        $properties = InfoboxCategoryProperty::where('infobox_id',$request->infobox_id)->get() ?? InfoboxCategoryProperty::all();
        $dataTypes = DataType::all();
        $view = view(
            'elfcms::admin.infobox.properties.content.list',
            [
                'properties' => $properties,
                'data_types' => $dataTypes,
            ]
        )->render();
        if (!$view) {
            $result['message'] = __('infobox::default.view_not_found');
            return $result;
        }
        $result['result'] = 'success';
        $result['message'] = __('infobox::default.data_saved_successfully');
        $result['data'] = $view;

        return $result;
    }
}
