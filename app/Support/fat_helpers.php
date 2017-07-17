<?php

/**
 * Generate alert box notification with close button.
 *     style is based on bootstrap 3.
 *
 * @param string $msg          notification message
 * @param string $type         type of notofication
 * @param bool   $close_button close button
 *
 * @return string notification with html tag
 */
function alert_box($msg, $type = 'warning', $close_button = true)
{
    $html = '';
    if ($msg != '') {
        $html .= '<div class="alert alert-'.$type.' alert-dismissible" role="alert">';
        if ($close_button) {
            $html .= '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
        }
        $html .= (is_array($msg)) ? implode('<br/ >', $msg) : $msg;
        $html .= '</div>';
    }

    return $html;
}

/**
 * Upload path.
 * 
 * @param  string $path
 * 
 * @return string complete path
 */
function upload_path($path = '')
{
    return base_path(). '/public/uploads/'. $path;
}

/**
 * Upload url.
 * 
 * @param  string $path
 * 
 * @return string complete url
 */
function upload_url($path = '')
{
    return url('/uploads/'. $path);
}

/**
 * Backend path.
 * 
 * @param  string $path
 * 
 * @return string full path
 */
function backend_path($path = '')
{
	return config('constant.location.backend_path'). $path;
}

/**
 * Backend url.
 * 
 * @param  string $path
 * 
 * @return string full url
 */
function backend_url($path = '')
{
	return config('constant.location.backend_url'). $path;
}

/**
 * Backend guard.
 * 
 * @return string guard
 */
function backend_guard()
{
	return config('constant.backend.guard');
}

/**
 * Load assets url.
 * 
 * @param  string $asset_url
 * 
 * @return string assets url
 */
function load_asset($asset_url)
{
    return ( env('APP_ENV') === 'production' ) ? asset($asset_url) : asset($asset_url);
}

/**
 * Load backend assets url.
 * 
 * @param  string $asset_url
 * 
 * @return string assets url
 */
function backend_assets_url($asset_url, $template = 'adminlte')
{
    return asset('backend/assets/'. $template. '/'. $asset_url);
}

/**
 * Get auth user.
 * 
 * @return mixed auth user
 */
function auth_user()
{
	if (auth()->guard(backend_guard())->check()) {
		return auth()->guard(backend_guard())->user();
	}

	return false;
}

/**
 * Check user superadmin status.
 * 
 * @return boolean 
 */
function is_superadmin()
{
	if (auth()->guard(backend_guard())->check()) {
		return auth()->guard(backend_guard())->user()->is_superadmin;
	}

	return false;
}

/**
 * Print Json with header.
 *
 * @param array $params parameters
 *
 * @return string encoded json
 */
function json_exit($json)
{
    header('Content-type: application/json');
    exit(
        json_encode($json)
    );
}

/**
 * Debug variable.
 *
 * @param mixed $params data to debug
 *
 * @return string print debug data
 */
function debugvar($params)
{
    echo '<pre>';
    print_r($params);
    echo '</pre>';
}
