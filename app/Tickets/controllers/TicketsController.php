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

namespace Tickets\Controllers;
use Tickets\Models as Models;

class TicketsController extends \BaseController {

	public function __construct() {
        $this->beforeFilter('csrf', array('on' => 'post'));
    }
	
	/**
	* Show all tickets regarding a user. If the user has the ticket manager role, display all tickets
	*/
	public function getIndex() {
		$tickets = Models\Ticket::involvedUser(\Auth::user())
					->orderBy('open', 'desc')
					->orderBy('created_at', 'desc')
					->get();
		return \View::make('tickets.index')->with('tickets', $tickets);
	}

	/**
	* Show the guest submit ticket form
	*/
	public function getGuest() {
		$categories = Models\TicketCategory::orderBy('position')->get();
		$categories_select_box = array();
		foreach ($categories as $category) {
			$categories_select_box[$category->id] = $category->title;
		}
		return \View::make('tickets.guest')->with('categories', $categories_select_box);
	}

	/**
	* Create new ticket - for auth users
	*/
	public function getCreate() {
		$categories = Models\TicketCategory::orderBy('position')->get();
		$categories_select_box = array();
		foreach ($categories as $category) {
			$categories_select_box[$category->id] = $category->title;
		}
		return \View::make('tickets.create')->with('categories', $categories_select_box);
	}



	/**
	* Show a ticket
	*/
	public function getShow($code) {
		$owner_select_box = array();
		$ticketOperators = \User::withRole('tickets operator')->get();
		foreach ($ticketOperators as $operator) {
			$owner_select_box[$operator->id] = $operator->email;
		}
		$ticket = Models\Ticket::with('comments')->where('code', '=', $code)->firstOrFail();
		return \View::make('tickets.show')->with(array('ticket' => $ticket, 'owners' => $owner_select_box));
	}

	/**
	* Save a new ticket
	*/
	public function postSave() {
		$ticket = new Models\Ticket;
		$ticket->author_email = \Input::get('email');
		$ticket->subject = \Input::get('subject');
		$ticket->description = \Input::get('description');
		$ticket->category_id = \Input::get('category');
		$manager = Models\TicketCategory::find(\Input::get('category'))->manager()->firstOrFail();
		$ticket->owner()->associate($manager);
		$ticket->code = strtoupper(str_random(10));
		$ticket->open = true;

		if($ticket->save())
		{
		\Mail::send('emails.tickets.confirm', array('ticket_code' => $ticket->code, 'author_email' => $ticket->author_email), function($message) use ($ticket){
    						$message->to($ticket->author_email, $ticket->author_email)->subject(trans('Thank you for your ticket submission'));
    					});
		return \Redirect::back()->with('status', trans('Thank you for your submission. We will answer you as soon as possible.'));	
		} else {
			return \Redirect::back()->with('error', trans('Please compile all required fields'))
			->withInput(\Input::except('password', 'password_confirm'));
		}
	}

	/**
	* Close a ticket
	*/
	public function postClose() {
		$ticket = Models\Ticket::find(\Input::get('id'));
		if ($ticket->isManager())
		{
			$comment = new Models\TicketComment;
			$comment->description = trans('ticket closed');
			$comment->author_email = \Auth::user()->email;
			$ticket->open = false;
			$ticket->comments()->save($comment);
			$ticket->save();
			return \Redirect::back();
		} else {
			return \Redirect::back()->with('error', trans('You are not authorized to close this ticket'));
		}
	}

	/**
	* Reopen a ticket
	*/
	public function postOpen() {
		$ticket = Models\Ticket::find(\Input::get('id'));
		if ($ticket->isManager())
		{
			$comment = new Models\TicketComment;
			$comment->description = trans('ticket open');
			$comment->author_email = \Auth::user()->email;
			$ticket->open = true;
			$ticket->comments()->save($comment);
			$ticket->save();
			return \Redirect::back();
		} else {
			return \Redirect::back()->with('error', trans('You are not authorized to re-open this ticket'));
		}
	}

	/**
	* Post a comment to a ticket
	*/
	public function postComment() {
		$ticket = Models\Ticket::find(\Input::get('ticket'));
		$comment = new Models\TicketComment;
		$comment->author_email = \Input::get('email');
		$comment->description = \Input::get('description');
		$ticket->comments()->save($comment);
		return \Redirect::back();
	}

	/**
	* change the ticket owner
	*/
	public function postChange()
	{
		$ticket = Models\Ticket::find(\Input::get('id'));
		if ($ticket->isManager())
		{
			$user = \User::find(\Input::get('owner'));
			$ticket->owner()->associate($user);
			$ticket->save();
			return \Redirect::back();
		} else {
			return \Redirect::back()->with('error', trans('You are not authorized to manage this ticket'));
		}
	}

	/**
	* With this funcione everyone can see the ticket, also guest users (@TODO: verify)
	*/
	public function getCode($code, $email = false) {
		$owner_select_box = array();
		$ticketOperators = \User::withRole('tickets operator')->get();
		foreach ($ticketOperators as $operator) {
			$owner_select_box[$operator->id] = $operator->email;
		}
		$ticket = Models\Ticket::with('comments')->where('code', '=', $code)->firstOrFail();
		return \View::make('tickets.show')->with(array('ticket' => $ticket, 'email' => $email, 'owners' => $owner_select_box));
	}

}