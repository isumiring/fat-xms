<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

use App\Models\Backend\UserLog;

class FatLib
{
    /**
     * Create log into db.
     * 
     * @param  string $action
     * @param  string $description
     * @param  array  $raw_data
     * 
     */
    public function createLog($action, $description, $raw_data = '')
    {
        $user_group_id = 0;
        $user_id = 0;
        if (Auth::guard(backend_guard())->check()) {
            $user_group_id = auth_user()->user_group_id;
            $user_id = auth_user()->id;
        }

        $log = UserLog::create([
            'user_group_id' => $user_group_id,
            'user_id'       => $user_id,
            'action'        => $action,
            'description'   => $description,
            'path'          => request()->fullUrl(),
            'ip_address'    => request()->ip(),
            'raw_data'      => ($raw_data != '') ? response()->json($raw_data) : response()->json(request()->input()),
        ]);
        if ($log) {
            Log::info('Log DB [Backend] created');
        }
    }

    /**
     * Get maximum value of a table.
     * 
     * @param  object $model
     * @param  string $field
     * 
     * @return integer maximum value
     */
    public function getMaxValue($model, $field = 'position')
    {
        return $model::max($field);
    }



    public function anuan()
    {
        // echo request()->ip();
        $anuan = response()->json(['ip_address' => request()->ip()]);
        return $anuan;
        return 'anuan nya inih';
    }
}
