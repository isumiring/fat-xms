<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

use DB;

class UserLog extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_group_id', 'user_id', 'action', 
        'description', 'ip_address', 'path', 'raw_data',
    ];
    
    /**
     * Define belongs to relationship.
     * 
     * @return object
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Backend\User');
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
                    FROM {$this->getConnection()->getTablePrefix()}{$this->user_group()->getModel()->getTable()}
                ) AS {$this->getConnection()->getTablePrefix()}{$this->user_group()->getModel()->getTable()}
                "), 
                $this->user_group()->getQualifiedOwnerKeyName(), '=', $this->user_group()->getQualifiedForeignKey()
            )
            ->leftJoin(DB::raw("(
                    SELECT id, username
                    FROM {$this->getConnection()->getTablePrefix()}{$this->user()->getModel()->getTable()}
                ) AS {$this->getConnection()->getTablePrefix()}{$this->user()->getModel()->getTable()}
                "), 
                $this->user()->getQualifiedOwnerKeyName(), '=', $this->user()->getQualifiedForeignKey()
            );

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
                $this->user_group()->getModel()->getTable(). '.group_name', 
                $this->user()->getModel()->getTable(). '.username'
            ])
            ->leftJoin(DB::raw("(
                    SELECT id, name as group_name
                    FROM {$this->getConnection()->getTablePrefix()}{$this->user_group()->getModel()->getTable()}
                ) AS {$this->getConnection()->getTablePrefix()}{$this->user_group()->getModel()->getTable()}
                "), 
                $this->user_group()->getQualifiedOwnerKeyName(), '=', $this->user_group()->getQualifiedForeignKey()
            )
            ->leftJoin(DB::raw("(
                    SELECT id, username
                    FROM {$this->getConnection()->getTablePrefix()}{$this->user()->getModel()->getTable()}
                ) AS {$this->getConnection()->getTablePrefix()}{$this->user()->getModel()->getTable()}
                "), 
                $this->user()->getQualifiedOwnerKeyName(), '=', $this->user()->getQualifiedForeignKey()
            );

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
}
