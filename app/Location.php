<?php
/**
 * Created by PhpStorm.
 * User: Bernadette
 * Date: 22/04/2017
 * Time: 1:33 PM
 */

namespace App;


class Location
{

//<?php
//
//namespace App;
//
//use Illuminate\Notifications\Notifiable;
//use Illuminate\Foundation\Auth\User as Authenticatable;
//
//class User extends Authenticatable
//{
//    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'address'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
//    protected $hidden = [
//        'password', 'remember_token',
//    ];


    /** ADDED!
     * Get the user's full name by concatenating the first and last names
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }
}
