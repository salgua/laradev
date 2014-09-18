<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function role()
	{
		return $this->belongsToMany('Role');
	}

	/**
	* Tickets Module relations
	*/

	/**
	* assigned tickets
	*/
	public function assigedTickets()
	{
		return $this->hasMany('Tickets\Models\Ticket', 'assigned_to'); 
	}

	/**
	* assigned categories for default ticket assigment
	*/
	public function ticketsCategories()
	{
		return $this->hasMany('Tickets\Models\TicketCategory', 'manager_id');
	}

}
