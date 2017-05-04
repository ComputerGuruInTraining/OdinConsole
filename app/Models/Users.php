<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;


class Users extends Eloquent
{
	
protected $table ="users";

/**
	 * Get the user's full name by concatenating the first and last names
	 *
	 * @return string
	 */
	public function getFullName()
	{
		return $this->first_name . ' ' . $this->last_name;
	}

}