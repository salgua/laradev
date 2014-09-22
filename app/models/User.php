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

	public function role() //was better name this function roles...
	{
		return $this->belongsToMany('Role');
	}

	/**
	* This function returns true if a user has a speciefied role name
	*/
	public function hasRole($roleName)
	{
		foreach ($this->role as $role) {
			if ($role->name = $roleName)
			{
				return true;
			}
		return false;
		}
	}

	public function scopeWithRole($query, $role)
	{
		return $query->whereHas('role', function($q) use ($role)
			{
				return $q->where('title', '=', $role);
			});
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
