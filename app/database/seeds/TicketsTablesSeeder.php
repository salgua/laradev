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

class TicketsTablesSeeder extends Seeder {

    public function run()
    {
        DB::table('tickets_categories')->delete();

        //administrator user
        $admin = User::find(1);

        //generic category
        $category = new Tickets\Models\TicketCategory;
        $category->title = 'generic';
        $category->manager()->associate($admin); //set default administrator as manager
        $category->save();

        //demo ticket
        $ticket = new Tickets\Models\Ticket;
        $ticket->author_email = 'demo@demo.it';
        $ticket->subject = "Test ticket";
        $ticket->description = "Test description";
        $ticket->category()->associate($category);
        $ticket->owner()->associate($admin);
        $ticket->open = true;
        $ticket->save();
    }
}