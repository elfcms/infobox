<?php

namespace Elfcms\Infobox\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Form;
use Elfcms\Infobox\Models\Infobox;
use Elfcms\Infobox\Models\InfoboxCategory;
use Elfcms\Infobox\Models\InfoboxItem;
use Illuminate\Http\Request;

class InfoboxController extends Controller
{
    /**
     * Update positions for form groups.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function lineOrder(Request $request, string $type)
    {
        $types = ['infobox','category','item'];

        $type = strtolower($type);

        if (!in_array($type,$types)) return false;

        if (!$request->ajax()) abort(403);

        $result = [
            'result' => 'error',
            'message' => '',
        ];

        $data = $request->all();

        //return [$data['type']];
        //return [$type];

        /* if (!empty($data['formId']) && $data['formId'] != $form->id) {
            $result['message'] = __('elfcms::default.error_saving_data');
        }

        if (empty($data['groups'])) {
            $result['message'] = __('elfcms::default.error_saving_data');
        } */

        /* //$groups = $form->groups;
        if (!empty($form->groups)) {
            foreach ($form->groups as $group) {
                if (!empty($data['groups'][$group->id])) {
                    $group->position = $data['groups'][$group->id];
                    $group->save();
                }
            }
        } */

        if ($type == 'infobox') {
            foreach ($data['lines'] as $id => $line) {
                if (empty($line['position'])) continue;
                $ib = Infobox::find($id);
                if (!empty($ib)) {
                    $ib->position = $line['position'];
                    $ib->save();
                }
            }
        }
        elseif ($type == 'category') {
            foreach ($data['lines'] as $id => $line) {
                if (empty($line['position'])) continue;
                $cat = InfoboxCategory::find($id);
                if (!empty($cat)) {
                    $cat->position = $line['position'];
                    $cat->save();
                }
            }
        }
        elseif ($type == 'item') {
            foreach ($data['lines'] as $id => $line) {
                if (empty($line['position'])) continue;
                $item = InfoboxItem::find($id);
                if (!empty($item)) {
                    $item->position = $line['position'];
                    $item->save();
                }
            }
            return $data;
        }

        $result['message'] = __('elfcms::default.changes_saved');
        $result['result'] = 'success';
        return $result;

    }

}
