<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Web\Controller;
use Illuminate\Http\Request;

use Auth;
use Validator;

use App\Models\Backend\UserGroup;
use App\Models\Backend\UserMenu;

class UserGroupController extends Controller
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
     * Auth user.
     * 
     * @var object|array
     */
    protected $auth_user;

    /**
     * Prefix routing.
     * 
     * @var string
     */
    protected $prefix_routes = 'user_group.';

    /**
     * Validation rules.
     * 
     * @var array
     */
    protected $validation_rules = [
        'name'   => 'required|min:3',
    ];

    /**
     * Class constructor.
     * 
     */
    public function __construct()
    {
        $this->model      = new UserGroup;
        $this->user_group = new UserMenu;
        $this->auth_user  = Auth::guard(backend_guard())->user();

        $this->prefix_routes = backend_path('.'. $this->prefix_routes);

        $this->parse['data_url']   = route($this->prefix_routes. 'index');
        $this->parse['add_url']    = route($this->prefix_routes. 'create');
        $this->parse['delete_url'] = route($this->prefix_routes. 'delete');
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
                'name'       => 'actions',
                'text'       => '',
                'searchable' => 'false',
                'orderable'  => 'false',
                'classname'  => 'text-center',
            ],
            [
                'name' => 'name',
                'text' => 'Name',
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
                $return['data'][$row]['DT_RowId']      = $record['id'];
                $return['data'][$row]['actions']       = '
                        <a href="'. route($this->prefix_routes. 'update', $record['id']). '" class="btn btn-info btn-sm"><i class="fa fa-pencil-square-o"></i></a> &nbsp; 
                        <a href="'. route($this->prefix_routes. 'authorize', $record['id']). '" class="btn btn-warning btn-sm"><i class="fa fa-universal-access"></i></a>
                    ';
                $return['data'][$row]['name']          = $record['name'];
                $return['data'][$row]['created_at']    = date('d-m-Y H:i', strtotime($record['created_at']));
                $return['data'][$row]['updated_at']    = date('d-m-Y H:i', strtotime($record['updated_at']));
            }
            return response()->json($return);
        }

        return view(backend_path('.layouts.partials.listdata'), $this->parse);
    }

    /**
     * Create page.
     * 
     * @param  Request $request
     * 
     * @return 
     */
    public function create(Request $request)
    {

        if ($request->isMethod('post')) {
            $post = $request->all();

            $validator = Validator::make($post, $this->validation_rules);

            if ($validator->fails()) {
                return redirect($this->parse['add_url'])->with('form_message', [
                        'message' => $validator->errors()->all(),
                        'status' => 'danger',
                    ])->withInput();
            }

            if ( ! is_superadmin()) {
                $post['is_superadmin'] = 0;
            }

            $data = $this->model->create($post);

            \FatLib::createLog('user_group_create', 'SUCCESS Create User Group ID: '. $data['id']);

            return redirect($this->parse['data_url'])->with('flash_message', [
                    'message' => 'Success',
                    'status'  => 'success',
                ]);
        }
        $this->parse['page_title'] = '[Add]';
        $this->parse['form_action'] = $this->parse['add_url'];

        return view($this->prefix_routes. 'form', $this->parse);
    }

    /**
     * Update page.
     * 
     * @param  Request $request
     * @param  integer $id
     * 
     * @return 
     */
    public function update(Request $request, $id = 0)
    {
        $this->parse['page_title'] = '[Edit]';
        $data = $this->model->find($id);
        if (! $id ||  ! $data || ($data['is_superadmin'] == 1 && ! is_superadmin())) {
            return redirect($this->parse['data_url'])->with('flash_message', [
                    'message' => 'Sorry. We couldn\'t find what your looking for.',
                    'status'  => 'warning',
                ]);
        }

        $this->parse['form_action'] = route($this->prefix_routes. 'update', $id);
        $this->parse['data'] = $data;

        if ($request->isMethod('post')) {
            $post = $request->all();

            $validator = Validator::make($post, $this->validation_rules);

            if ($validator->fails()) {
                return redirect($this->parse['form_action'])->with('form_message', [
                        'message' => $validator->errors()->all(),
                        'status' => 'danger',
                    ])->withInput();
            }

            if ( ! is_superadmin()) {
                $post['is_superadmin'] = 0;
            }

            $data->fill($post)->save();

            \FatLib::createLog('user_group_update', 'SUCCESS Update User Group ID: '. $data['id']);

            return redirect($this->parse['data_url'])->with('flash_message', [
                    'message' => 'Success',
                    'status'  => 'success',
                ]);
        }

        return view($this->prefix_routes. 'form', $this->parse);
    }

    /**
     * Authorization page.
     * 
     * @param  Request $request
     * @param  integer $id
     * 
     * @return 
     */
    public function authorizer(Request $request, $id = 0)
    {
        $this->parse['page_title'] = '[Authorization]';
        $data = $this->model->find($id);
        if (! $id ||  ! $data || ($data['is_superadmin'] == 1 && ! is_superadmin())) {
            return redirect($this->parse['data_url'])->with('flash_message', [
                    'message' => 'Sorry. We couldn\'t find what your looking for.',
                    'status'  => 'warning',
                ]);
        }

        $this->parse['form_action'] = route($this->prefix_routes. 'authorize', $id);
        $this->parse['data'] = $data;

        $this->parse['authorized_menus'] = $this->user_menu->getAuthMenuByGroup($id)->groupBy('id');
        $this->parse['user_menus'] = $this->user_menu->getAllRecords()->threaded('parent_id');

        if ($request->isMethod('post')) {
            $post = $request->all();

            $validator = Validator::make($post, [
                    'user_menus' => 'required|array|min:1'
                ]);

            if ($validator->fails()) {
                return redirect($this->parse['form_action'])->with('form_message', [
                        'message' => $validator->errors()->all(),
                        'status' => 'danger',
                    ])->withInput();
            }

            $data->user_menus()->sync($post['user_menus']);

            \FatLib::createLog('user_group_authorize', 'SUCCESS Authorize User Group ID: '. $data['id'], $post);

            return redirect($this->parse['data_url'])->with('flash_message', [
                    'message' => 'Success',
                    'status'  => 'success',
                ]);
        }

        return view($this->prefix_routes. 'authorize', $this->parse);
    }

    /**
     * Delete record.
     * 
     * @param  Request $request
     * 
     * @return json|array return 
     */
    public function delete(Request $request)
    {
        if ($request->isMethod('delete') && $request->ajax()) {
            $id = $request->id;
            $data = $this->model->getModelById($id);
            if (! $id ||  ! $data) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Failed to delete. Please try again.'
                ]);
            }

            $own_group = false;

            if (is_array($id)) {
                foreach ($id as $key => $value) {
                    if (auth_user()->user_group_id == $value) {
                        $own_group = true;
                        break;
                    }
                }
            } else {
                if (auth_user()->user_group_id == $id) {
                    $own_group = true;
                }
            }
            if ($own_group == true) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'You can\'t delete your own group.'
                ]);
            }
            $this->model->deleteModelById($id);

            \FatLib::createLog('user_group_delete', 'SUCCESS Delete User Group', $id);

            return response()->json([
                'status'  => 'success',
                'message' => 'Data has been deleted.'
            ]);
        }
    }
}
