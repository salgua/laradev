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

class TicketComment extends \Eloquent {

	protected $table = 'tickets_comments';

	protected $touches = array('ticket');

	public static function boot()
	{
		parent::boot();

		//Purify the HTML description on comment creating or updating
		TicketComment::creating(function($comment){
			$comment->description = \Purifier::clean($comment->description);
		});

		TicketComment::updating(function($comment){
			$comment->description = \Purifier::clean($comment->description);
		});

		//send notifications on comment create
		TicketComment::created(function($ticketComment)
		{
			$ticket = $ticketComment->ticket()->get()->first();
		    \Mail::send('emails.tickets.updated', array('ticket' => $ticket), function($message) use ($ticket){
				$message->to($ticket->author_email, $ticket->author_email)
				->cc($ticket->owner->email, $ticket->owner->email)
				->subject(sprintf(trans('Ticket %s updated'), $ticket->code));
			});
		});
	}

	public function ticket()
	{
		return $this->belongsTo('Tickets\Models\Ticket', 'ticket_id');
	}

	public function parent()
	{
		return $this->belongsTo('Tickets\Models\TicketComment', 'parent_id');
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

}