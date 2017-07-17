<?php

namespace App\Models\Backend\Collection;

use Illuminate\Database\Eloquent\Collection;

class UserMenuCollection extends Collection
{

	public function threaded($key)
	{
		$auth_menu = parent::groupBy($key);

		if (count($auth_menu)) {
			$auth_menu['root'] = $auth_menu['0'];

			unset($auth_menu['0']);
		}

		return $auth_menu;
	}

}