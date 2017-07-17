<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'value', 'site_id'
    ];

    /**
     * Disabled the created_at and updated_at
     * 
     * @var boolean
     */
    public $timestamps = false;

    /**
     * Define belongs to relationship.
     * 
     * @return object
     */
    public function site()
    {
        return $this->belongsTo('App\Models\Backend\Site');
    }
}
