<?php

use Tickets\Models;

Tickets\Models\Ticket::updated(function($ticket)
{
    Mail::send('emails.tickets.updated', array('ticket' => $ticket), function($message) use ($ticket){
		$message->to($ticket->author_email, $ticket->author_email)
		->cc($ticket->owner->email, $ticket->owner->email)
		->subject(sprintf(trans('Ticket %s updated'), $ticket->code));
	});
});


Tickets\Models\TicketComment::created(function($ticketComment)
{
	$ticket = $ticketComment->ticket()->get()->first();
    Mail::send('emails.tickets.updated', array('ticket' => $ticket), function($message) use ($ticket){
		$message->to($ticket->author_email, $ticket->author_email)
		->cc($ticket->owner->email, $ticket->owner->email)
		->subject(sprintf(trans('Ticket %s updated'), $ticket->code));
	});
});