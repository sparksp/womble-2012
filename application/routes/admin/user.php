<?php

return array(

	'GET /admin/user' => array('before' => 'role:admin', function()
	{
		$users = User::paginate();

		return View::make('master')
			->with('title', 'User Admin')
			->nest('content', 'admin.user.index', array(
				'content' => $users,
			));
	}),

	'GET /admin/user/(:num)' => array('before' => 'role:admin', function($id)
	{
		$user = User::find($id);

		if ($user)
		{
			return View::make('master')
				->with('title', $user->name)
				->nest('content', 'admin.user.show', array('user' => $user));
		}
		else
		{
			return Response::error(404);
		}
	}),

	'GET /admin/user/(:num)/edit' => array('before' => 'role:admin', function($id)
	{
		$user = User::find($id);

		if ($user)
		{
			return View::make('master')
				->with('title', 'Edit: '. $user->name)
				->nest('content', 'admin.user.edit', array('user' => $user));
		}
		else
		{
			return Response::error(404);
		}
	}),

	'PUT /admin/user/(:num)' => array('before' => 'role:admin|csrf', function($id)
	{
		$user = User::find($id);

		if ($user)
		{
			$errors = $user->validate();
			if (count($errors->all()) > 0)
			{
				return View::make('master')
					->with('title', 'Edit: '. $user->name)
					->nest('content', 'admin.user.edit', array('user' => $user, 'errors' => $errors));
			}
			else
			{
				$user->save();
				return Redirect::to('admin/user/'.$user->id)
					->with('message', 'User updated.');
			}
		}
		else
		{
			return Response::error(404);
		}
	}),

	'GET /admin/user/(:num)/disable' => array('before' => 'role:admin', function($id)
	{
		$user = User::find($id);
		if ($user)
		{
			if ($user->id !== Auth::user()->id)
			{
				return View::make('master')
					->with('title', 'Disable: '.$user->name)
					->nest('content', 'admin.user.disable', array('user' => $user));
			}
			else
			{
				return Redirect::to('admin/user/'.$user->id)->with('error', 'You cannot disable your own account!');
			}
		}
		else
		{
			return Response::error(404); // Not Found
		}
	}),
	'DELETE /admin/user/(:num)/password' => array('before' => 'role:admin|csrf', 'do' => function($id)
	{
		$user = User::find($id);
		if ($user)
		{
			if ($user->id !== Auth::user()->id)
			{
				$user->password = '';
				$user->save();
				return Redirect::to('admin/user')->with('message', 'User disabled.');
			}
			else
			{
				return Response::error(405); // Method Not Allowed
			}
		}
		else
		{
			return Response::error(404); // Not Found
		}
	}),

	// @todo change/set password method
	// @todo create user method

);
