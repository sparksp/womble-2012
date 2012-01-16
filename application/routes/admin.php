<?php

return array(

	'GET /admin' => array('before' => 'role:admin', function()
	{
		return View::make('master')
			->with('title', 'Admin')
			->nest('content', 'admin.index');
	}),

);
