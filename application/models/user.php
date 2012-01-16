<?php

use Laravel\Database\Eloquent\Model as Eloquent;

/**
 * User
 *
 * @author Phill Sparks <me@phills.me.uk>
 * @version 1.0
 */
class User extends Eloquent {
	/**
	 * @var string
	 */
	public static $table = 'user';

	/**
	 * @var bool
	 */
	public static $timestamps = true;

	/**
	 * Validate form input and set it on the model.
	 *
	 * @param array $data
	 * @return Laravel\Messages Error messages
	 */
	public function validate(array $data = null)
	{
		$rules = array(
			'name'  => 'required',
			'email' => 'required|email',
			'admin' => 'in:0,1',
		);

		if (is_null($data))
		{
			$data = Input::all();
		}

		$validator = new Validator($data, $rules);

		if ($validator->valid())
		{
			$this->name  = Arr::get($data, 'name');
			$this->email = Arr::get($data, 'email');
			$this->admin = Arr::get($data, 'admin', 0);
		}

		if (Auth::user()->id == $this->id)
		{
			$this->admin = 1; // Silently prevent user from removing admin from themselves
		}

		return $validator->errors;
	}

}