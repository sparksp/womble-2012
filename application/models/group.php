<?php

/**
 * A booking group
 *
 * @author Phill Sparks <me@phills.me.uk>
 * @version 1.0
 */
class Group extends Eloquent {

	/**
	 * @var string
	 */
	public static $table = 'group';

	/**
	 * @var bool
	 */
	public static $timestamps = true;

	/**
	 * @param $id
	 * @return Group
	 */
	public static function findOrCreate($id)
	{
		if ( ! $id or ! $group = static::find($id))
		{
			$group = new static;
		}
		return $group;
	}

	/**
	 * @return array
	 */
	public function attendees()
	{
		return $this->has_many('Attendee');
	}

	/**
	 * Validate form input and set it on the model.
	 *
	 * @param array $data
	 * @return Laravel\Messages
	 */
	public function validate(array $data = null)
	{
		// Get the data if it's not been passed in
		if (is_null($data))
		{
			$data = Input::all();
		}

		// Build an array of rules
		$rules = array(
			'name'     => 'required',
			'district' => 'required',
			'contact'  => 'required',
			'email'    => 'required|email',
			'attendee' => 'attendee',
		);

		// Validate!
		$validator = new Group_Validator($data, $rules);

		if ($validator->valid())
		{
			$this->name     = Arr::get($data, 'name');
			$this->district = Arr::get($data, 'district');
			$this->contact  = Arr::get($data, 'contact');
			$this->email    = Arr::get($data, 'email');
			$this->phone    = Arr::get($data, 'phone');
		}

		return $validator->errors;
	}

}

/**
 * Group Validator
 */
class Group_Validator extends Validator {

	/**
	 * @param $attributes
	 * @param $rules
	 * @param array $messages
	 */
	public function __construct($attributes, $rules, $messages = array())
	{
		$messages = array_merge(array(
			'attendee' => 'You must provide at least one attendee.',
		), $messages);

		parent::__construct($attributes, $rules, $messages);
	}

	/**
	 * Check that at at least one attendee has been provided.
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters
	 * @return bool
	 */
	public function validate_attendee($attribute, $value, $parameters)
	{
		$value = array_filter($value, array('Attendee', 'rowNotEmpty'));

		return count($value) > 0;
	}

}