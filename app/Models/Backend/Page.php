<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'title', 'teaser', 'description', 
        'type', 'slug_url', 'module', 'ext_link', 
        'primary_image', 'thumbnail_image', 'background_image',
        'icon_image', 'position', 'is_published', 'is_featured',
        'is_featured', 'is_header', 'is_footer', 
    ];

    /**
     * Get relation with same table as a parent.
     * 
     * @return object
     */
    public function parent()
    {
        return $this->belongsTo($this, 'parent_id', 'id');
    }

    /**
     * New collection.
     * 
     * @param  array  $models
     * @param  string $param
     * 
     * @return object
     */
    public function newCollection(array $models = [], $param = '')
    {
    	return new Collection\PageCollection($models, $param);
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

        if (isset($params['conditions'])) {
            foreach ($params['conditions'] as $row => $condition) {
            	$operator = (isset($condition['operator'])) ? $condition['operator'] : '=';
            	$data = $data->where($condition['field'], $operator, $condition['value']);
            }
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
        $data = $this->with(['parent']);

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

        if (isset($params['conditions'])) {
            foreach ($params['conditions'] as $row => $condition) {
            	$operator = (isset($condition['operator'])) ? $condition['operator'] : '=';
            	$data = $data->where($condition['field'], $operator, $condition['value']);
            }
        }

        if (isset($params['row_from']) && isset($params['length'])) {
            $data = $data->skip($params['row_from'])->take($params['length']);
        }

		$order_field = (isset($params['order_field']) && $params['order_field'] != '') ? $params['order_field'] : $this->getTable(). '.position';
		$order_sort  = (isset($params['order_sort']) && $params['order_sort'] != '') ? $params['order_sort'] : 'asc';

        $data = $data
        	->orderBy($order_field, $order_sort)
        	->get();

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
