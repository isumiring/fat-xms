<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Web\Controller;
use Illuminate\Http\Request;

use Validator;

use App\Models\Backend\Page;

use Image;

class PageController extends Controller
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
     * Destination path (for upload). 
     * 
     * @var string
     */
    protected $destination_path = 'pages/';

    /**
     * Prefix routing.
     * 
     * @var string
     */
    protected $prefix_routes = 'page.';

    /**
     * Validation rules.
     * 
     * @var array
     */
    protected $validation_rules = [
        'parent_id'        => 'required|numeric',
        'title'            => 'required|min:3',
        'type'             => 'required|in:static_page,module,external_link',
        'position'         => 'required|numeric|min:1',
        'primary_image'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // 'thumbnail_image'  => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // 'background_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // 'icon_image'       => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',

    ];

    /**
     * Class constructor.
     * 
     */
    public function __construct()
    {
        $this->model = new Page;

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
                'name' => 'title',
                'text' => 'Title',
            ],
            [
                'name' => 'parent_name',
                'text' => 'Parent',
                'searchable' => 'false',
                'orderable'  => 'false',
            ],
            [
                'name' => 'type',
                'text' => 'Page Type',
            ],
            [
                'name' => 'position',
                'text' => 'Position',
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
                $return['data'][$row]['actions']     = '<a href="'. route($this->prefix_routes. 'update', $record['id']). '" class="btn btn-info btn-sm"><i class="fa fa-pencil-square-o"></i></a>';
                $return['data'][$row]['title']       = $record['title'];
                $return['data'][$row]['position']    = $record['position'];
                $return['data'][$row]['type']        = ucwords(str_replace('_', ' ', $record['type']));
                $return['data'][$row]['parent_name'] = ($record['parent_id'] == 0 || $record['parent_id'] == '') ? 'ROOT' : $record['parent']['title'];
                $return['data'][$row]['created_at']  = date('d-m-Y H:i', strtotime($record['created_at']));
                $return['data'][$row]['updated_at']  = date('d-m-Y H:i', strtotime($record['updated_at']));
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

            $data = $this->model->create($post);

            if ($request->hasFile('primary_image')) {
                $file = $request->file('primary_image');

                $filename = str_slug($post['title'], '-'). '_image'. date('YmdHi'). '.'. $file->getClientOriginalExtension();
                $file->move(upload_path($this->destination_path), $filename);
                // image resize
                Image::make(upload_path($this->destination_path. $filename))->resize(config('constant.default.img_thumb_width'), config('constant.default.img_thumb_height'), function($constraint) {
                    $constraint->aspectRatio();
                })->save(upload_path($this->destination_path. 'tmb_'. $filename));

                // insert to db
                $data['primary_image'] = $filename;

                $data->save();
            }

            \FatLib::createLog('page_create', 'SUCCESS Create Page ID: '. $data['id'], $data);

            return redirect($this->parse['data_url'])->with('flash_message', [
                    'message' => 'Success',
                    'status'  => 'success',
                ]);
        }
        $this->parse['page_title'] = '[Add]';
        $this->parse['parents'] = $this->model->getAllRecords()->threaded('parent_id');
        $this->parse['form_action'] = $this->parse['add_url'];
        $this->parse['max_position'] = \FatLib::getMaxValue($this->model) + 1;

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
        if (! $id ||  ! $data) {
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

            $data->fill($post)->save();

            if ($request->hasFile('primary_image')) {
                if ($data['primary_image'] != '' && file_exists(upload_path($this->destination_path. $data['primary_image']))) {
                    @unlink(upload_path($this->destination_path. $data['primary_image']));
                }
                $file = $request->file('primary_image');

                $filename = str_slug($post['title'], '-'). '_image'. date('YmdHi'). '.'. $file->getClientOriginalExtension();
                $file->move(upload_path($this->destination_path), $filename);
                // image resize
                Image::make(upload_path($this->destination_path. $filename))->resize(config('constant.default.img_thumb_width'), config('constant.default.img_thumb_height'), function($constraint) {
                    $constraint->aspectRatio();
                })->save(upload_path($this->destination_path. 'tmb_'. $filename));

                // insert to db
                $data['primary_image'] = $filename;

                $data->save();
            }

            \FatLib::createLog('page_update', 'SUCCESS Update Page ID: '. $data['id']);

            return redirect($this->parse['data_url'])->with('flash_message', [
                    'message' => 'Success',
                    'status'  => 'success',
                ]);
        }

        $this->parse['parents'] = $this->model->getAllRecords()->threaded('parent_id');

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

            // delete image
            if (is_array($id)) {
                foreach ($data as $key => $user) {
                    if ($user['primary_image'] != '' && file_exists(upload_path($this->destination_path. $user['primary_image']))) {
                        @unlink(upload_path($this->destination_path. $user['primary_image']));
                        @unlink(upload_path($this->destination_path. 'tmb_'. $user['primary_image']));
                    }
                }
            } else {
                if ($data['primary_image'] != '' && file_exists(upload_path($this->destination_path. $data['primary_image']))) {
                    @unlink(upload_path($this->destination_path. $data['primary_image']));
                    @unlink(upload_path($this->destination_path. 'tmb_'. $data['primary_image']));
                }
            }

            $this->model->deleteModelById($id);

            \FatLib::createLog('page_delete', 'SUCCESS Delete Page', $id);

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
            $id   = $request->id;
            $type = $request->type;
            $data = $this->model->getModelById($id);
            if (! $id ||  ! $data) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Failed to delete. Please try again.'
                ]);
            }
            // check if the image is exists
            if ($data[$type] != '' && file_exists(upload_path($this->destination_path. $data[$type]))) {
                @unlink(upload_path($this->destination_path. $data[$type]));
            }

            $data->{$type} = '';

            $data->save();

            \FatLib::createLog('page_delete_picture', 'SUCCESS Delete Page Picture ID: '. $data['id'], $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Image has been deleted.'
            ]);
        }
    }
}
