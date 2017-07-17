<?php

namespace App\Http\Controllers\Web\Backend;

use App\Http\Controllers\Web\Controller;
use Illuminate\Http\Request;

use Validator;
use Image;

use App\Models\Backend\Site;
use App\Models\Backend\SiteSetting;

class SiteController extends Controller
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
    protected $site_setting;

    /**
     * Destination path (for upload). 
     * 
     * @var string
     */
    protected $destination_path = 'sites/';

    /**
     * Prefix routing.
     * 
     * @var string
     */
    protected $prefix_routes = 'site.';

    /**
     * Validation rules.
     * 
     * @var array
     */
    protected $validation_rules = [
        'site_name'         => 'required|min:3',
        'site_logo'         => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        'site_image_header' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ];

    /**
     * Class constructor.
     * 
     */
    public function __construct()
    {
        $this->model = new Site;

        $this->prefix_routes = backend_path('.'. $this->prefix_routes);

        $this->parse['upload_path'] = $this->destination_path;

        $this->parse['index_url']          = route($this->prefix_routes. 'index');
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
        $data = $this->model->getModelById(1);

        $this->parse['page_title'] = '[Edit]';
        $this->parse['form_action'] = $this->parse['index_url'];
        $this->parse['data'] = $data;

        // json_exit($data['site_settings']);

        if ($request->isMethod('post')) {
            $post = $request->all();

            $validator = Validator::make($post, [
                'site_settings' => 'array|required'
            ]);

            if ($validator->fails()) {
                return redirect($this->parse['form_action'])->with('form_message', [
                        'message' => $validator->errors()->all(),
                        'status'  => 'danger',
                    ])->withInput();
            }

            $settings = [];
            foreach ($post['site_settings'] as $key => $setting) {
                $settings[] = new SiteSetting([
                    'type'  => $key,
                    'value' => ($setting !== null) ? $setting : '',
                ]);
            }

            // update data
            $data->fill($post)->save();
            // delete data before make new input
            $data->site_settings()->delete();
            // save the relation
            $data->site_settings()->saveMany($settings);

            if ($request->hasFile('site_logo')) {
                if ($data['site_logo'] != '' && file_exists(upload_path($this->destination_path. $data['site_logo']))) {
                    @unlink(upload_path($this->destination_path. $data['site_logo']));
                }
                $file = $request->file('site_logo');

                $filename = 'site_logo_'. date('YmdHi'). '.'. $file->getClientOriginalExtension();
                $file->move(upload_path($this->destination_path), $filename);

                // insert to db
                $data['site_logo'] = $filename;

                $data->save();
            }

            if ($request->hasFile('site_image_header')) {
                if ($data['site_image_header'] != '' && file_exists(upload_path($this->destination_path. $data['site_image_header']))) {
                    @unlink(upload_path($this->destination_path. $data['site_image_header']));
                }
                $file = $request->file('site_image_header');

                $filename = 'site_image_header_'. date('YmdHi'). '.'. $file->getClientOriginalExtension();
                $file->move(upload_path($this->destination_path), $filename);

                // insert to db
                $data['site_image_header'] = $filename;

                $data->save();
            }

            \FatLib::createLog('site_create', 'SUCCESS Edit Site', $data);

            return redirect($this->parse['index_url'])->with('flash_message', [
                    'message' => 'Success',
                    'status'  => 'success',
                ]);
        }

        return view($this->prefix_routes. 'form', $this->parse);
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

            \FatLib::createLog('site_delete_picture', 'SUCCESS Delete Site Image ID: '. $data['id'], $id);

            return response()->json([
                'status' => 'success',
                'message' => 'Image has been deleted.'
            ]);
        }
    }
}
