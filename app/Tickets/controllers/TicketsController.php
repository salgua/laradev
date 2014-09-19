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
	
	public function getIndex() {
		return "OK";
	}

	public function getGuest() {
		$categories = Models\TicketCategory::all();
		$categories_select_box = array();
		foreach ($categories as $category) {
			$categories_select_box[$category->id] = $category->title;
		}
		return \View::make('tickets.guest')->with('categories', $categories_select_box);
	}

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
		}
	}

	public function postComment() {
		$ticket = Models\Ticket::find(\Input::get('ticket'));
		$comment = new Models\TicketComment;
		$comment->author_email = \Input::get('email');
		$comment->description = \Input::get('description');
		$ticket->comments()->save($comment);
		return \Redirect::back();
	}

	public function getCode($code, $email = false) {
		$ticket = Models\Ticket::with('comments')->where('code', '=', $code)->firstOrFail();
		return \View::make('tickets.show')->with(array('ticket' => $ticket, 'email' => $email));
	}

}