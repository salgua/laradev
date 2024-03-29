<?php
// Laradev - a Laravel web app boilerplate 
// Copyright (c) 2014 Deved S.a.s. di Salvatore Guarino & C - sg@deved.it

// Permission is hereby granted, free of charge, to any person obtaining a copy
// of this software and associated documentation files (the "Software"), to deal
// in the Software without restriction, including without limitation the rights
// to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
// copies of the Software, and to permit persons to whom the Software is
// furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in all
// copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
// OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
// SOFTWARE.

namespace Tickets\Models;

class Ticket extends \Eloquent {

	protected $table = 'tickets';

	public static function boot()
	{
		parent::boot();

		//Purify the HTML description o ticket creating or updating
		Ticket::creating(function($ticket){
			$ticket->description = \Purifier::clean($ticket->description);
			$validator = \Validator::make(
				array('description' => $ticket->description),
				array('description' => 'required')
			);
			if ($validator->fails())
			{
				return false;
			}
		});
		Ticket::updating(function($ticket){
			$ticket->description = \Purifier::clean($ticket->description);
		});

		//Send notifications on ticket create and update
		Ticket::updated(function($ticket)
		{
		    \Mail::send('emails.tickets.updated', array('ticket' => $ticket), function($message) use ($ticket){
				$message->to($ticket->author_email, $ticket->author_email)
				->cc($ticket->owner->email, $ticket->owner->email)
				->subject(sprintf(trans('Ticket %s updated'), $ticket->code));
			});
		});

		Ticket::created(function($ticket)
		{
		    \Mail::send('emails.tickets.updated', array('ticket' => $ticket), function($message) use ($ticket){
				$message->to($ticket->owner->email, $ticket->owner->email)
				->subject(sprintf(trans('New Ticket %s from help desk'), $ticket->code));
			});
		});
	}

	public function category()
	{
		return $this->belongsTo('Tickets\Models\TicketCategory', 'category_id');
	}

	public function owner()
	{
		return $this->belongsTo('\User', 'assigned_to');
	}

	public function comments()
	{
		return $this->hasMany('Tickets\Models\TicketComment', 'ticket_id');
	}

	/**
	*@return: screen name of the author, if exist else email
	*/
	public function getAuthorScreenName()
	{
		$author = \User::whereEmail($this->author_email)->first();
		if ($author)
		{
			return $author->getScreenName();
		}
		return $this->author_email;
	}


	/**
	* Check if a user is a mangager of a ticket
	*/
	public function isManager()
	{
		if (\Auth::check())
		{
			$user = \Auth::user();
			if ($this->owner->email == $user->email || $user->role()->where('title', '=', 'tickets manager')->first())
			{
				return true;
			}
			return false;
		} 

		return false;
		
	}

	/**
	*Query scope to find tickets with a user involved as author or manager
	*/
	public function scopeInvolvedUser($query, $user)
	{
		$manager_role = false;
		if (\Auth::check())
		{
			$manager_role = \Auth::user()->role()->where('title', '=', 'tickets manager')->first();
		}
		if ($manager_role)
		{
			return $query->where('created_at', '>', '0'); //if user is tickets manager return all tickets
		}

		return $query->whereRaw('assigned_to = ? or author_email = ?', array($user->id, $user->email));
	}

}