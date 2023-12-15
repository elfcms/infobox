<?php

namespace Elfcms\Infobox\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use Elfcms\Elfcms\Models\Form;
use Illuminate\Http\Request;

class InfoboxController extends Controller
{
    /**
     * Update positions for form groups.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Elfcms\Elfcms\Models\Form  $form
     * @return bool
     */
    public function loneOrder(Request $request, string $type)
    {
        $types = ['infobox','category','item'];

        if (!in_array($type,$types)) return false;

        if (!$request->ajax()) abort(403);

        $result = [
            'result' => 'error',
            'message' => '',
        ];

        /* $data = $request->all();

        if (!empty($data['formId']) && $data['formId'] != $form->id) {
            $result['message'] = __('elfcms::default.error_saving_data');
        }

        if (empty($data['groups'])) {
            $result['message'] = __('elfcms::default.error_saving_data');
        }

        //$groups = $form->groups;
        if (!empty($form->groups)) {
            foreach ($form->groups as $group) {
                if (!empty($data['groups'][$group->id])) {
                    $group->position = $data['groups'][$group->id];
                    $group->save();
                }
            }
        }

        $result['message'] = __('elfcms::default.changes_saved');
        $result['result'] = 'success'; */

        return $result;

    }

}
