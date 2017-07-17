<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'site_name', 'site_url', 'site_path', 
        'site_logo', 'site_image_header', 'is_default',
    ];
    
    /**
     * Define has many relationship.
     * 
     * @return object
     */
    public function site_settings()
    {
        return $this->hasMany('App\Models\Backend\SiteSetting');
    }

    /**
     * Get site info.
     * 
     * @return array|boolean $data
     */
    public function getSiteInfo()
    {
        $data = $this->with(['site_settings'])
            ->where('is_default', 1)
            ->orderBy($this->getKeyName(), 'desc')
            ->first();

        if ($data) {
            foreach ($data['site_settings'] as $key => $setting) {
                $data['site_settings'][$setting['type']] = $setting['value'];
            }
        }
        
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
        $data = $this->with(['site_settings' => function ($query) {
            $query->orderBy('id', 'asc');
        }]);
        if (is_array($id)) {
            return $data->whereIn($this->getKeyName(), $id)->get();
        }

        return $data->where($this->getKeyName(), $id)->first();
    }
}
