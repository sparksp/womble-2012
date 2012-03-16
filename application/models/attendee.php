<?php

use Laravel\Database\Eloquent\Model as Eloquent;
use Laravel\Validator;

/**
 * An attendee
 *
 * @author Phill Sparks <me@phills.me.uk>
 * @version 1.0
 */
class Attendee extends Eloquent {

	/**
	 * @var string
	 */
	public static $table = 'attendee';

	/**
	 * @var array
	 */
	public static $activities = array(
		'canoeing', 'caving', 'climbing', 'walking',
		// fully booked:
		// 'biking',
	);

	/**
	 * Validate form input and set it on the model.
	 *
	 * @param array $data
	 * @return bool
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
			'saturday' => 'required|in:'.implode(',', static::$activities).'|activity:sunday',   // in...
			'sunday'   => 'required|in:'.implode(',', static::$activities).'|activity:saturday', // in...
		);

		// Validate!
		$validator = new Attendee_Validator($data, $rules);

		if ($validator->valid())
		{
			$this->name     = Arr::get($data, 'name');
			$this->saturday = Arr::get($data, 'saturday');
			$this->sunday   = Arr::get($data, 'sunday');
			$this->extra    = Arr::get($data, 'extra');
		}

		return $validator->errors;
	}

	/**
	 * Test whether a given $row is empty.
	 *
	 * @param array $row
	 * @return bool
	 */
	public static function rowEmpty(array $row)
	{
		return empty($row['name']) and empty($row['saturday']) and empty($row['sunday']);
	}

	/**
	 * Test whether a given $row is not empty.
	 *
	 * @param array $row
	 * @return bool
	 */
	public static function rowNotEmpty(array $row)
	{
		return ! empty($row['name']) or ! empty($row['saturday']) or ! empty($row['sunday']);
	}
}

/**
 * Attendee Validator
 */
class Attendee_Validator extends Validator {

	/**
	 * @param $attributes
	 * @param $rules
	 * @param array $messages
	 */
	public function __construct($attributes, $rules, $messages = array())
	{
		$messages = array_merge(array(
			'activity' => 'The activities must not be the same.',
		), $messages);

		parent::__construct($attributes, $rules, $messages);
	}

	/**
	 * You can only specify each activity once per attendee, this checks
	 * $value against the other attributes listed in $parameters.
	 *
	 * @param $attribute
	 * @param $value
	 * @param $parameters List of other attributes to check against.
	 * @return bool
	 */
	public function validate_activity($attribute, $value, $parameters)
	{
		foreach ($parameters as $field)
		{
			if ($this->attributes[$field] == $value) return false;
		}
		return true;
	}

}