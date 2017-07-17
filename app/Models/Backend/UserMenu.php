<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

use Auth;

class UserMenu extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id', 'menu', 'file', 
        'icon_tags', 'position', 'is_superadmin'
    ];

    /**
     * Define belongs to many relationship.
     * 
     * @return object
     */
    public function user_groups()
    {
        return $this->belongsToMany('App\Models\Backend\UserGroup', 'user_menu_group');
    }

    /**
     * Get relation with same table recursively.
     * 
     * @return object
     */
    public function childrens()
    {
        return $this->hasMany('App\Models\Backend\UserMenu', 'parent_id', 'id')->with('childrens');
    }

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
     * Check if user/group have access rights to menu.
     *
     * @param int $user_group_id
     * @param int $user_menu_id
     *
     * @return bool true/false
     */
    public function checkUserHaveRightsMenu($user_group_id, $user_menu_id)
    {
        $total_records = $this
            ->where($this->user_groups()->getQualifiedForeignKeyName(), $user_menu_id)
            ->where($this->user_groups()->getQualifiedRelatedKeyName(), $user_group_id)
            ->join($this->user_groups()->getTable(), $this->user_groups()->getQualifiedForeignKeyName(), '=', $this->getTable().'.'. $this->getKeyName());

        return $total_records->count();
    }

    /**
     * Get auth menu by group id.
     * 
     * @return array|boolean $data
     */
    public function getAuthMenuByGroup($user_group_id = 0)
    {
        if ( ! $user_group_id) {
            return;
        }
        $data = $this
        		->select($this->getTable(). '.*')
                ->where($this->user_groups()->getQualifiedRelatedKeyName(), $user_group_id)
                ->orderBy('position', 'asc')
                ->orderBy($this->getTable(). '.'. $this->getKeyName(), 'desc')
                ->join($this->user_groups()->getTable(), $this->user_groups()->getQualifiedForeignKeyName(), '=', $this->getTable().'.'. $this->getKeyName())
                ->get();

        return $data;
    }

    /**
     * Get menu info by path/file.
     * 
     * @param  string $path
     * 
     * @return array|boolean $data
     */
    public function getUserMenuInfoByPath($path = '')
    {
        if ( ! $path) {
            return;
        }
        $data = $this->whereRaw("LCASE(file) = '". $path. "'")->first();

        return $data;
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
    	return new Collection\UserMenuCollection($models, $param);
    }

    /**
     * Active Menu Ids 
     *     return array for listing hierarcy active menu
     *     
     * @param int $parent_id
     * @param array &$menus
     *
     * @return array $ids;
     */
    public function getActiveMenuWithParent($parent_id = 0, &$menus = [])
    {
        if ( ! $parent_id) {
            return $menus;
        }
        
        $data = $this
                ->where($this->getKeyName(), $parent_id)
                ->first();
        if ($data) {
            $menus[] = $data;
            $parent = $this->getActiveMenuWithParent($data['parent_id'], $menus);
        }

        return $menus;
    }

    /**
     * Breadcrumbs.
     * 
     * @param array $menus
     *
     * @return array breadcrumbs list
     */
    public function getBreadcrumbs($menus, $current_menu_id = '')
    {
    	$breadcrumbs = [];
    	foreach ($menus as $key => $menu) {
    		$url = ($menu['file'] != '' && $menu['file'] != '#') ? url(backend_url(). '/'. $menu['file']) : '#';
    		if ($current_menu_id != '' && $current_menu_id == $menu['id']) {
    			$url = '#';
    		}
            $breadcrumbs[] = [
                'text'  => (($menu['icon_tags'] != '') ? '<i class="'. $menu['icon_tags']. '"></i> ' : ''). $menu['menu'],
                'url'   => $url,
                'class' => '',
            ];
    	}

        return $breadcrumbs;
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
     * Get excluded menus (disabled from option).
     * 
     * @param  array  $menus
     * @param  integer $user_menu_id
     * @param  array  &$excluded_menus
     * 
     * @return array $excluded_menus excluded menus
     */
    public function getExcludedMenus($menus, $user_menu_id = '', &$excluded_menus = [])
    {
    	$collections = $menus->groupBy('parent_id');
    	if ($user_menu_id) {
    		foreach ($collections[$user_menu_id] as $key => $collection) {
	    		$excluded_menus[] = $collection['id'];
    			if (isset($collections[$collection['id']])) {
		    		$this->getExcludedMenus($menus, $collection['id'], $excluded_menus);
    			}
    		}
    	}

    	return $excluded_menus;
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
