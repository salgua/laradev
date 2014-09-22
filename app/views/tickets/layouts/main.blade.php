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
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>{{{ Config::get('tickets.title') }}}</title>
		<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <meta name="robots" content="noindex">
		{{ HTML::style('css/bootstrap.min.css') }}
		{{ HTML::style('css/font-awesome.min.css') }}
		{{ HTML::style('css/ionicons.min.css') }}
		{{ HTML::style('css/morris/morris.css') }}
		{{-- HTML::style('css/jvectormap/jquery-jvectormap-1.2.2.css') --}}
		{{ HTML::style('css/datepicker/datepicker3.css'); }}
		{{ HTML::style('css/daterangepicker/daterangepicker-bs3.css'); }}
		{{ HTML::style('css/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css'); }}
		{{ HTML::style('css/AdminLTE.css'); }}
        {{ HTML::style('css/custom.css'); }}
        @yield('custom_css')
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
	</head>
	<body class="{{{isset($bodyClass) ? $bodyClass : 'skin-black'}}}">
        <header class="header">
            <a href="/" class="logo">{{{ Config::get('tickets.title') }}}</a>
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        @if (Auth::check())
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <i class="glyphicon glyphicon-user"></i>
                                        <span>{{ Auth::user()->email }} <i class="caret"></i></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- Menu Body -->
                                    <li class="user-body">
                                        <div class="col-xs-12 text-center">
                                            {{ link_to('tickets', trans('Tickets')) }}
                                        </div>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <!-- a href="#" class="btn btn-default btn-flat">Profile</a -->
                                        </div>
                                        <div class="pull-right">
                                            {{ link_to('logout', trans('Sign out'), array('class' => 'btn btn-default btn-flat')) }}
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        @else
                            <li>{{link_to('login', trans('login'))}}</li>
                            <li>{{link_to('signup', trans('register'))}}</li>
                        @endif
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <aside class="left-side sidebar-offcanvas">
                <section class="sidebar">
                    <ul class="sidebar-menu">
                    @if (Auth::check())
                        <li>
                            <a href="{{url('tickets')}}"><i class="fa fa-envelope"></i><span>Tickets</span></a>
                        </li>
                        <li>
                            <a href="{{url('tickets/create')}}"><i class="fa fa-file-text-o"></i><span>New Ticket</span></a>
                        </li>
                    @else
                        <li><a href="{{url('login')}}"><i class="fa fa-sign-in"></i><span>{{trans('Log in')}}</span></a></li>
                        <li><a href="{{url('signup')}}"><i class="fa fa-user"></i><span>{{trans('Register')}}</span></a></li>
                    @endif
                    </ul>
                </section>
            </aside>
			<aside class="right-side">
                {{-- Flashing error and status message message --}}
                @if (Session::get('error'))
                    <div class="content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-danger alert-dismissable alert-top">
                                    <i class="fa fa-ban"></i>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{{ Session::get('error') }}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (Session::get('status'))
                    <div class="content">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="alert alert-info alert-dismissable alert-top">
                                    <i class="fa fa-info"></i>
                                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                    {{{ Session::get('status') }}}
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @yield('content')
            </aside>
		</div>
		<!-- jQuery 2.0.2 -->
		{{ HTML::script('bower_components/jquery/dist/jquery.min.js') }}
        <!-- jQuery UI 1.10.3 -->
        {{ HTML::script('js/jquery-ui-1.10.3.min.js') }}
        <!-- Bootstrap -->
        {{ HTML::script('js/bootstrap.min.js') }}
        <!-- Morris.js charts -->
        <!-- script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script -->
        {{ HTML::script('js/plugins/morris/morris.min.js') }}
        <!-- Sparkline -->
        {{ HTML::script('js/plugins/sparkline/jquery.sparkline.min.js') }}
        <!-- jvectormap -->
        {{-- HTML::script('js/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') --}}
        {{-- HTML::script('js/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') --}}
        <!-- jQuery Knob Chart -->
        {{ HTML::script('js/plugins/jqueryKnob/jquery.knob.js') }}
        <!-- daterangepicker -->
        {{ HTML::script('js/plugins/daterangepicker/daterangepicker.js') }}
        <!-- datepicker -->
        {{ HTML::script('js/plugins/datepicker/bootstrap-datepicker.js') }}
        <!-- Bootstrap WYSIHTML5 -->
        {{ HTML::script('js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}
        <!-- iCheck -->
        {{ HTML::script('js/plugins/iCheck/icheck.min.js') }}
        <!-- AdminLTE App -->
        {{ HTML::script('js/AdminLTE/app.js') }}
        <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
        {{-- HTML::script('js/AdminLTE/dashboard.js') --}}
        <!-- AdminLTE for demo purposes -->
        {{-- HTML::script('js/AdminLTE/demo.js') --}}
        {{ HTML::script('js/angular.min.js') }}
        {{ HTML::script('js/ui-utils.min.js') }}
        {{ HTML::script('bower_components/ng-remote-validate/release/ngRemoteValidate.0.4.1.min.js') }}
        @yield('angular')
            <?php
                //$data = Session::all();
                //var_dump($data);
            ?>
	</body>
</html>