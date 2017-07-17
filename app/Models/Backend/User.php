<?php

namespace App\Models\Backend;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Hash;
use DB;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_group_id', 'username', 'password', 'name',
        'email', 'avatar', 'user_activation', 'user_status',
        'themes', 'remember_token', 'last_login_at'
    ];

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [
        'is_superadmin'
    ];

    /**
     * The attributes excluded from the model's form.
     *
     * @var array
     */
    protected $hidden = [
        'password'
    ];

    /**
     * Set default cast for password field.
     * 
     * @param string $password
     */
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }

    /**
     * Define belongs to relationship.
     * 
     * @return object
     */
    public function user_group()
    {
        return $this->belongsTo('App\Models\Backend\UserGroup');
    }

    /**
     * Get User Info by username.
     * 
     * @param  string $username
     * 
     * @return array|boolean $user_data
     */
    public function getInfoByUsername($username)
    {
        $user_data = $this
                ->whereRaw("LCASE(username) = '". strtolower($username). "'")
                ->first();

        if ($user_data) {
            // return info auth user
            return $user_data;
        }

        return false;
    }

    /**
     * Count all records.
     *
     * @param array $params
     *
     * @return integer $total_records total records
     */
    public function countAllRecords($params = [])
    {
    	$total_rows = $this
            ->leftJoin(DB::raw("(
                    SELECT id, name as group_name
                    from {$this->getConnection()->getTablePrefix()}{$this->user_group()->getModel()->getTable()}
                ) as {$this->getConnection()->getTablePrefix()}{$this->user_group()->getModel()->getTable()}
                "), 
                $this->user_group()->getQualifiedOwnerKeyName(), '=', $this->user_group()->getQualifiedForeignKey()
            );
        
    	// check if user is superadmin
        if ( ! auth_user()->is_superadmin) {
            $total_rows = $total_rows
                ->where($this->getTable(). '.is_superadmin', 0);
        }

        if (isset($params['search_value']) && $params['search_value'] != '') {
            $total_rows = $total_rows->where(function($query) use($params) {
                $i = 0;
                foreach ($params['search_field'] as $row => $val) {
                    if ($val['searchable'] == 'true') {
                        if ($i == 0) {
                            $query->whereRaw("LCASE({$val['data']}) like '%" .strtolower($params['search_value']). "%'");
                        } else {
                            $query->orwhereRaw("LCASE({$val['data']}) like '%" .strtolower($params['search_value']). "%'");
                        }
                        $i++;
                    }
                }
            });
        }

        $total_rows = $total_rows->count();

        return $total_rows;
    }

    /**
     * Get all data.
     *
     * @param array $params
     *
     * @return array|boolean $data
     */
    public function getAllRecords($params = [])
    {
        $data = $this
            ->select([
                $this->getTable(). '.*',
                $this->user_group()->getModel()->getTable(). '.group_name'
            ])
            ->leftJoin(DB::raw("(
                    SELECT id, name as group_name
                    from {$this->getConnection()->getTablePrefix()}{$this->user_group()->getModel()->getTable()}
                ) as {$this->getConnection()->getTablePrefix()}{$this->user_group()->getModel()->getTable()}
                "), 
                $this->user_group()->getQualifiedOwnerKeyName(), '=', $this->user_group()->getQualifiedForeignKey()
            );

        // check if user is superadmin
        if ( ! auth_user()->is_superadmin) {
            $data = $data
                ->where($this->getTable(). '.is_superadmin', 0);
        }

        if (isset($params['search_value']) && $params['search_value'] != '') {
            $data = $data->where(function($query) use($params) {
                $i = 0;
                foreach ($params['search_field'] as $row => $val) {
                    if ($val['searchable'] == 'true') {
                        if ($i == 0) {
                            $query->whereRaw("LCASE({$val['data']}) like '%" .strtolower($params['search_value']). "%'");
                        } else {
                            $query->orwhereRaw("LCASE({$val['data']}) like '%" .strtolower($params['search_value']). "%'");
                        }
                        $i++;
                    }
                }
            });
        }

        if (isset($params['row_from']) && isset($params['length'])) {
            $data = $data->skip($params['row_from'])->take($params['length']);
        }

        $order_field = (isset($params['order_field']) && $params['order_field'] != '') ? $params['order_field'] : $this->getTable(). '.'. $this->getKeyName();
        $order_sort = (isset($params['order_sort']) && $params['order_sort'] != '') ? $params['order_sort'] : config('constant.default.sort_order');

        $data = $data->orderBy($order_field, $order_sort)->get();

        return $data;
    }

    /**
     * Get Model data by ID.
     * 
     * @param  mixed $id
     * 
     * @return array|boolean $data
     */
    public function getModelById($id)
    {
        $data = $this->with(['user_group']);
        if (is_array($id)) {
            return $data->whereIn($this->getKeyName(), $id)->get();
        }

        return $data->where($this->getKeyName(), $id)->first();
    }

    /**
     * Delete record(s) from this model.
     * 
     * @param  int|array $id
     */
    public function deleteModelById($id)
    {
        if (is_array($id)) {
            $this->whereIn($this->getKeyName(), $id)->delete();
        } else {
            $this->where($this->getKeyName(), $id)->delete();
        }
    }
    
}
