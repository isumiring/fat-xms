<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Web\Controller;
use Illuminate\Http\Request;

use App\Models\Backend\UserLog;

class UserLogController extends Controller
{
    /**
     * Set globally for this controller.
     * 
     * @var array
     */
    protected $parse = [];

    /**
     * So we can call a model in every method.
     * 
     * @var object|array
     */
    protected $model;

    /**
     * Prefix routing.
     * 
     * @var string
     */
    protected $prefix_routes = 'user_log.';

    /**
     * Validation rules.
     * 
     * @var array
     */
    protected $validation_rules = [
        'parent_id' => 'required|numeric',
        'menu'      => 'required|min:3',
        'file'      => 'required',
        'position'  => 'required|numeric|min:1',

    ];

    /**
     * Class constructor.
     * 
     */
    public function __construct()
    {
        $this->model = new UserLog;

        $this->prefix_routes = backend_path('.'. $this->prefix_routes);

        $this->parse['data_url']    = route($this->prefix_routes. 'index');
        $this->parse['hide_add']    = true;
        $this->parse['hide_delete'] = true;
    }

    /**
     * Index/listing page.
     * 
     * @param  Request $request
     * 
     * @return layout
     */
    public function index(Request $request)
    {
        $this->parse['page_title'] = '[List]';
        $this->parse['tables'] = [
            [
                'name' => 'username',
                'text' => 'Username',
            ],
            [
                'name' => 'group_name',
                'text' => 'Group',
            ],
            [
                'name' => 'action',
                'text' => 'Action',
            ],
            [
                'name' => 'description',
                'text' => 'Description',
            ],
            [
                'name'       => 'created_at',
                'text'       => 'Created',
                'searchable' => 'false',
            ],
            [
                'name'       => 'updated_at',
                'text'       => 'Updated',
                'searchable' => 'false',
            ],
        ];

        if ($request->isMethod('post')) {
            $post = $request->all();
            $params['search_value'] = $post['search']['value'];
            $params['search_field'] = $post['columns'];
            if (isset($post['order'])) {
                $params['order_field'] = $post['columns'][$post['order'][0]['column']]['data'];
                $params['order_sort']  = $post['order'][0]['dir'];
            }
            $params['start']  = $post['start'];
            $params['length'] = $post['length'];
            $count_all_records         = $this->model->countAllRecords();
            $count_filtered_records    = $this->model->countAllRecords($params);
            $records                   = $this->model->getAllRecords($params);
            $return                    = [];
            $return['draw']            = $post['draw'];
            $return['recordsTotal']    = $count_all_records;
            $return['recordsFiltered'] = $count_filtered_records;
            $return['data']            = [];
            foreach ($records as $row => $record) {
                $return['data'][$row]['DT_RowId']    = $record['id'];
                $return['data'][$row]['username']    = ($record['username'] != '') ? $record['username'] : 'Guest';
                $return['data'][$row]['group_name']  = ($record['group_name'] != '') ? $record['group_name'] : 'Guest';
                $return['data'][$row]['action']      = $record['action'];
                $return['data'][$row]['description'] = $record['description'];
                $return['data'][$row]['created_at']  = date('d-m-Y H:i', strtotime($record['created_at']));
                $return['data'][$row]['updated_at']  = date('d-m-Y H:i', strtotime($record['updated_at']));
            }
            return response()->json($return);
        }

        return view(backend_path('.layouts.partials.listdata'), $this->parse);
    }
}
