<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Web\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use Auth;
use Validator;
use Hash;
use Image;

use App\Models\Backend\User;
use App\Models\Backend\UserGroup;

class UserController extends Controller
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
     * Load model.
     * 
     * @var object|array
     */
    protected $user_group;

    /**
     * Auth user.
     * 
     * @var object|array
     */
    protected $auth_user;

    /**
     * Destination path (for upload). 
     * 
     * @var string
     */
    protected $destination_path = 'users/';

    /**
     * Prefix routing.
     * 
     * @var string
     */
    protected $prefix_routes = 'user.';

    /**
     * Validation rules.
     * 
     * @var array
     */
    protected $validation_rules = [
        'name'   => 'required|min:3',
        'avatar' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    /**
     * Class constructor.
     * 
     */
    public function __construct()
    {
        $this->model      = new User;
        $this->user_group = new UserGroup;
        $this->auth_user  = Auth::guard(backend_guard())->user();

        $this->prefix_routes = backend_path('.'. $this->prefix_routes);

        $this->parse['upload_path'] = $this->destination_path;

        $this->parse['data_url']           = route($this->prefix_routes. 'index');
        $this->parse['add_url']            = route($this->prefix_routes. 'create');
        $this->parse['delete_url']         = route($this->prefix_routes. 'delete');
        $this->parse['delete_picture_url'] = route($this->prefix_routes. 'delete_picture');
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
                'name' => 'username',
                'text' => 'Username',
            ],
            [
                'name' => 'name',
                'text' => 'Name',
            ],
            [
                'name' => 'email',
                'text' => 'Email',
            ],
            [
                'name' => 'group_name',
                'text' => 'Group',
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
                $return['data'][$row]['DT_RowId']   = $record['id'];
                $return['data'][$row]['actions']    = '<a href="'. route($this->prefix_routes. 'update', $record['id']). '" class="btn btn-info btn-sm"><i class="fa fa-pencil-square-o"></i></a>';
                $return['data'][$row]['name']       = $record['name'];
                $return['data'][$row]['username']   = $record['username'];
                $return['data'][$row]['email']      = $record['email'];
                $return['data'][$row]['group_name'] = $record['group_name'];
                $return['data'][$row]['created_at'] = date('d-m-Y H:i', strtotime($record['created_at']));
                $return['data'][$row]['updated_at'] = date('d-m-Y H:i', strtotime($record['updated_at']));
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

            $this->validation_rules = array_merge(
                [
                    'email'    => 'required|email|unique:'. $this->model->getTable(),
                    'username' => 'required|valid_username|min:5|unique:'. $this->model->getTable(),
                    'password' => 'required|confirmed|min:6',
                ],
                $this->validation_rules);

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

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');

                $filename = 'user_avatar_'. $data['id']. '_'. date('YmdHi'). '.'. $file->getClientOriginalExtension();
                $file->move(upload_path($this->destination_path), $filename);
                // image resize
                Image::make(upload_path($this->destination_path. $filename))->resize(config('constant.default.img_thumb_width'), config('constant.default.img_thumb_height'), function($constraint) {
                    $constraint->aspectRatio();
                })->save(upload_path($this->destination_path. 'tmb_'. $filename));
                // insert to db
                $data['avatar'] = $filename;

                $data->save();
            }

            \FatLib::createLog('user_create', 'SUCCESS Create User ID: '. $data['id'], $request->except('password'));

            return redirect($this->parse['data_url'])->with('flash_message', [
                    'message' => 'Success',
                    'status'  => 'success',
                ]);
        }
        $this->parse['groups']      = $this->user_group->getAllRecords(['order_field' => 'name']);
        $this->parse['page_title']  = '[Add]';
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
        if (! $id ||  ! $data || ($data['is_superadmin'] == 1 && ! auth_user()->is_superadmin)) {
            return redirect($this->parse['data_url'])->with('flash_message', [
                    'message' => 'Sorry. We couldn\'t find what your looking for.',
                    'status'  => 'warning',
                ]);
        }

        $this->parse['form_action'] = route($this->prefix_routes. 'update', $id);
        $this->parse['data'] = $data;

        if ($request->isMethod('post')) {
            $post = $request->all();

            $this->validation_rules = array_merge(
                [
                    'email' => [
                        'required',
                        'email', 
                        Rule::unique($this->model->getTable())->ignore($data['id']),
                    ],
                    'username' => [
                        'required',
                        'valid_username',
                        'min:5', 
                        Rule::unique($this->model->getTable())->ignore($data['id']),
                    ],
                ],
                $this->validation_rules);

            if ($post['password'] != '') {

                $this->validation_rules = array_merge(
                    [
                        'password' => 'required|confirmed|min:6',
                    ], 
                    $this->validation_rules);
            }

            $validator = Validator::make($post, $this->validation_rules);

            if ($validator->fails()) {
                return redirect($this->parse['form_action'])->with('form_message', [
                        'message' => $validator->errors()->all(),
                        'status' => 'danger',
                    ])->withInput();
            }

            $data->fill($post)->save();

            if ($request->hasFile('avatar')) {
                if ($data['avatar'] != '' && file_exists(upload_path($this->destination_path. $data['avatar']))) {
                    @unlink(upload_path($this->destination_path. $data['avatar']));
                    @unlink(upload_path($this->destination_path. 'tmb_'. $data['avatar']));
                }
                $file = $request->file('avatar');

                $filename = 'user_avatar_'. $this->auth_user['id']. '_'. date('YmdHi'). '.'. $file->getClientOriginalExtension();
                $file->move(upload_path($this->destination_path), $filename);
                // image resize
                Image::make(upload_path($this->destination_path. $filename))->resize(config('constant.default.img_thumb_width'), config('constant.default.img_thumb_height'), function($constraint) {
                    $constraint->aspectRatio();
                })->save(upload_path($this->destination_path. 'tmb_'. $filename));
                // insert to db
                $data['avatar'] = $filename;

                $data->save();
            }

            \FatLib::createLog('user_update', 'SUCCESS Update User ID: '. $data['id'], $request->except('password'));

            return redirect($this->parse['data_url'])->with('flash_message', [
                    'message' => 'Success',
                    'status'  => 'success',
                ]);
        }

        $this->parse['groups'] = $this->user_group->getAllRecords(['order_field' => 'name']);

        return view($this->prefix_routes. 'form', $this->parse);
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

            $own_account = false;

            if (is_array($id)) {
                foreach ($id as $key => $value) {
                    if (auth_user()->id == $value) {
                        $own_account = true;
                        break;
                    }
                }
            } else {
                if (auth_user()->user_group_id == $id) {
                    $own_account = true;
                }
            }
            if ($own_account == true) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'You can\'t delete your own account.'
                ]);
            }
            // delete image
            if (is_array($id)) {
                foreach ($data as $key => $user) {
                    if ($user['avatar'] != '' && file_exists(upload_path($this->destination_path. $user['avatar']))) {
                        @unlink(upload_path($this->destination_path. $user['avatar']));
                        @unlink(upload_path($this->destination_path. 'tmb_'. $user['avatar']));
                    }
                }
            } else {
                if ($data['avatar'] != '' && file_exists(upload_path($this->destination_path. $data['avatar']))) {
                    @unlink(upload_path($this->destination_path. $data['avatar']));
                    @unlink(upload_path($this->destination_path. 'tmb_'. $data['avatar']));
                }
            }
            $this->model->deleteModelById($id);

            \FatLib::createLog('user_delete', 'SUCCESS Delete User', $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Data has been deleted.'
            ]);
        }
    }

    /**
     * Delete picture.
     * 
     * @param  Request $request
     * 
     * @return json|array return 
     */
    public function deletePicture(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $id = $request->id;
            $data = $this->model->getModelById($id);
            if (! $id ||  ! $data) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Failed to delete. Please try again.'
                ]);
            }
            // check if the image is exists
            if ($data['avatar'] != '' && file_exists(upload_path($this->destination_path. $data['avatar']))) {
                @unlink(upload_path($this->destination_path. $data['avatar']));
                @unlink(upload_path($this->destination_path. 'tmb_'. $data['avatar']));
            }

            $data->avatar = '';

            $data->save();

            \FatLib::createLog('user_delete_picture', 'SUCCESS Delete User Picture ID: '. $data['id'], $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Image has been deleted.'
            ]);
        }
    }

    /**
     * Profile page.
     * 
     * @return [type] [description]
     */
    public function profile(Request $request)
    {
        $data = $this->auth_user;
        $this->parse['data'] = $data;
        $this->parse['page_title']  = 'Profile';
        $this->parse['form_action'] = route(backend_path('.user.profile'));
        $this->parse['change_password_url'] = route(backend_path('.user.changepassword'));

        if ($request->isMethod('post')) {
            $post = $request->except('avatar');

            $this->validation_rules = array_merge(
                [
                    'email' => [
                        'required',
                        'email', 
                        Rule::unique($this->model->getTable())->ignore($data['id']),
                    ],
                ],
                $this->validation_rules);

            $validator = Validator::make($post, $this->validation_rules);

            if ($validator->fails()) {
                return redirect($this->parse['form_action'])->with('form_message', [
                        'message' => $validator->errors()->all(),
                        'status' => 'danger',
                    ])->withInput();
            }

            if ($request->hasFile('avatar')) {
                if ($data['avatar'] != '' && file_exists(upload_path($this->destination_path. $data['avatar']))) {
                    @unlink(upload_path($this->destination_path. $data['avatar']));
                    @unlink(upload_path($this->destination_path. 'tmb_'. $data['avatar']));
                }
                $file = $request->file('avatar');

                $filename = 'user_avatar_'. $this->auth_user['id']. '_'. date('YmdHi'). '.'. $file->getClientOriginalExtension();
                $file->move(upload_path($this->destination_path), $filename);
                // image resize
                Image::make(upload_path($this->destination_path. $filename))->resize(config('constant.default.img_thumb_width'), config('constant.default.img_thumb_height'), function($constraint) {
                    $constraint->aspectRatio();
                })->save(upload_path($this->destination_path. 'tmb_'. $filename));
                // insert to db
                $post['avatar'] = $filename;
            }

            $data->fill($post)->save();

            \FatLib::createLog('profile_update', 'SUCCESS Update Profile' , $post);

            return redirect($this->parse['form_action'])->with('form_message', [
                    'message' => 'Success',
                    'status' => 'success'
                ]);
        }

        return view(backend_path('.user.profile'), $this->parse);
    }

    /**
     * Change user password.
     * 
     * @param  Request $request
     * 
     * @return Json
     */
    public function changePassword(Request $request)
    {
        if ($request->isMethod('post') && $request->ajax()) {
            $data = $this->model->getModelById($this->auth_user['id']);

            $validator = Validator::make($request->all(), [
                'old_password' => 'required',
                'password'     => 'required|min:6|confirmed|different:old_password',
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => alert_box($validator->errors()->all(), 'danger')
                ]);
            }
            if ( ! Hash::check($request['old_password'], $data['password'])) {
                return response()->json([
                    'status'  => 'failed',
                    'message' => alert_box('Your old password didn\'t match.', 'danger')
                ]);
            }

            $data->password = $request['password'];

            $data->save();

            \FatLib::createLog('profile_change_password', 'SUCCESS Change Password Profile', $data->id);

            return response()->json([
                'status'   => 'success',
                'message'  => alert_box('Your Password has been changed.', 'success'), 
                'redirect' => route(backend_path('.user.profile'))
            ]);
        }
    }
}
