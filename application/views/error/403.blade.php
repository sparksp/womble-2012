{{View::make('master.header', array('title' => Auth::guest() ? 'Log in' : 'Forbidden'))->render()}}
<article>
@if (Auth::guest())
	<h1>Log in</h1>

	{{Form::open(URL::to_login(), 'POST', array('class' => Form::TYPE_HORIZONTAL))}}
	{{Form::token()}}
	{{Form::hidden('from', Input::get('from', Request::uri() === 'login' ? '' : Request::uri()))}}

	{{Form::field('email', 'email', 'E-mail Address', array(Input::get('email')))}}
	{{Form::field('password', 'password', 'Password')}}
	{{Form::field('labelled_checkbox', 'remember', '', array('Use a '.HTML::link('cookies', 'cookie', array('title' => 'Find out more about the cookies we use and how to delete them', 'rel' => 'twipsy', 'target' => '_blank')).' to remember my details', 'yes'))}}

	{{Form::actions(Form::submit("Log in", array('class' => 'primary')))}}

	{{Form::close()}}
@else
	<h1>Forbidden <small>Error 403</small></h1>

	<h2>What does this mean?</h2>

	<p>
		We couldn't find the page you requested on our servers. We're really sorry
		about that. It's our fault, not yours. We'll work hard to get this page
		back online as soon as possible.
	</p>

	<p>
		Perhaps you would like to go to our {{HTML::link('/', 'home page')}}?
	</p>
@endif
</article>
{{View::make('master.footer')->render()}}