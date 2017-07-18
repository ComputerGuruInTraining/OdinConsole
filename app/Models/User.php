<?php


namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model as Eloquent;


class User extends Eloquent
{
	
protected $table ="users";

//use Notifiable;
//
//    /**
//     * The attributes that are mass assignable.
//     *
//     * @var array
//     */
//    protected $fillable = [
//        'name', 'email', 'password',
//    ];
//
//    /**
//     * The attributes that should be hidden for arrays.
//     *
//     * @var array
//     */
//    protected $hidden = [
//        'password', 'remember_token',
//    ];
//
//
//    /** ADDED!
//     * Get the user's full name by concatenating the first and last names
//     *
//     * @return string
//     */
//
///**
//	 * Get the user's full name by concatenating the first and last names
//	 *
//	 * @return string
//	 */
//	public function getFullName()
//	{
//		return $this->first_name . ' ' . $this->last_name;
//	}

}