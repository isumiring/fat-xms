<?php

namespace App\Models\Backend\Collection;

use Illuminate\Database\Eloquent\Collection;

class PageCollection extends Collection
{
	public function threaded($key)
	{
		$pages = parent::groupBy($key);

		if (count($pages)) {
			$pages['root'] = $pages['0'];

			unset($pages['0']);
		}

		return $pages;
	}

}