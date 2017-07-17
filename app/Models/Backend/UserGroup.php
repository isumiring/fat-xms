<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'is_superadmin'
    ];

    /**
     * Define belongs to many relationship.
     * 
     * @return object
     */
    public function user_menus()
    {
        return $this->belongsToMany('App\Models\Backend\UserMenu', 'user_menu_group');
    }
    
    /**
     * Define has many relationship.
     * 
     * @return object
     */
    public function users()
    {
        return $this->hasMany('App\Models\Backend\User');
    }

    /**
     * Count records.
     *
     * @param array $params
     *
     * @return int $total_records total records
     */
    public function countAllRecords($params = [])
    {
        $total_rows = $this;
        // check if user is superadmin
        if ( ! is_superadmin()) {
            $total_rows = $total_rows
                ->where('is_superadmin', 0);
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
     * Get all group data.
     *
     * @param array $params
     *
     * @return array|boolean $data
     */
    public function getAllRecords($params = [])
    {
        $data = $this;
        // check if user is superadmin
        if ( ! is_superadmin()) {
            $data = $data
                ->where('is_superadmin', 0);
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
        $data = $this;
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
