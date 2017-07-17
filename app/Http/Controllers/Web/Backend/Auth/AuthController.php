<?php

namespace App\Http\Controllers\Web\Backend\Auth;

use App\Http\Controllers\Web\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Validator;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Backend\User;
use Auth;
use Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

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
     * Set username for login.
     * 
     * @var string
     */
    protected $username = 'username';

    /**
     * Default guard.
     * 
     * @var string
     */
    protected $guard;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:backend')->except('logout');

        $this->guard = backend_guard();

        $this->model = new User;
    }

    /**
     * Login page.
     * 
     * @return view layout
     */
    public function login(Request $request)
    {
        $loginPath  = backend_path('.auth.login');
        $redirectTo = route(backend_path('.index'));

        $this->parse['head_title'] = 'Login';

        if ($request->isMethod('post')) {
            $validator = Validator::make($request->all(), [
                'username' => 'required|exists:'. $this->model->getTable(),
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => alert_box($validator->errors()->all(), 'danger')
                    ]);
                }
                return redirect()
                        ->withErrors($validator)->route($loginPath);
            }
            $param_auth = [
                'username' => $request['username'],
                'password' => $request['password'],
                'user_status' => 1,
            ];

            if (Auth::guard($this->guard)->attempt($param_auth)) {

                $user = $this->model->getInfoByUsername($request['username']);
                // update last login
                $user->last_login_at = date('Y-m-d H:i:s');

                $user->save();

                \FatLib::createLog('login', 'SUCCESS User login ID: '. $user['id'], $request->except('password'));

                if (request()->session()->exists('tmp_login_redirect')) {
                    $redirectTo = request()->session()->pull('tmp_login_redirect', backend_path('.index'));
                }

                if ($request->ajax()) {
                    return response()->json(['redirect_auth' => $redirectTo]);
                }

                redirect($redirectTo);
            } else {
                $error_message = 'Your credential is incorrect.';

                \FatLib::createLog('login', 'FAILED User login', $request->except('password'));

                if ($request->ajax()) {
                    return response()->json([
                        'status' => 'failed',
                        'message' => alert_box($error_message, 'danger')
                    ]);
                }
                return redirect()
                        ->withErrors([$error_message])->route($loginPath);
            }
        }

        $this->parse['form_action'] = route($loginPath);

        return view($loginPath, $this->parse);
    }

    /**
     * Logout url.
     * 
     * @return redirect
     */
    public function logout()
    {
        Auth::guard($this->guard)->logout();

        return redirect()->route(backend_path('.auth.login'));
    }
}
