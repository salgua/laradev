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

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('roles')->delete();
        DB::table('users')->delete();

        //add administrator role
        $role_admin = Role::create(array(
            'id' => 1,
            'title' => 'administrator'
        ));

        //add tickets manager role
        $role_ticket = Role::create(array(
            'id' => 2,
            'title' => 'tickets manager'
        ));

        //add tickets operator role
        Role::create(array(
            'id' => 3,
            'title' => 'tickets operator'
        ));

        $user = User::create(array(
        	'id' => 1,
            'email' => 'info@deved.it',
            'password' => Hash::make('laradev'),
            'active' => true
        ));

        $user->role()->attach($role_admin->id);
        $user->role()->attach($role_ticket->id);
        $user->save();


    }
}