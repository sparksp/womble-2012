<?php

/**
 * Booking Controller
 *
 * @author Phill Sparks <me@phills.me.uk>
 * @version 1.0
 */
class Booking_Controller extends Controller
{
	/**
	 * @var bool
	 */
	public $restful = true;

	/**
	 * @var array
	 */
	protected $activities = array(
		'' => '-- Choose Activity --',
		'canoeing' => 'Canoeing',
		'caving' => 'Caving',
		'climbing' => 'Climbing',
		'biking' => 'Mountain Biking',
		'walking' => 'Walking',
	);

	/**
	 *
	 */
	public function __construct()
	{
		$this->filter('before', 'csrf')->on('post', 'put');
	}

	/**
	 * GET /booking/new
	 *
	 * @return Laravel\View
	 */
	public function get_new()
	{
		$group = new Group();

		$attendees = array();
		for ($i = 0; $i < 12; ++$i)
		{
			$attendees[] = new Attendee();
		}

		return View::make('master')
			->with('title', 'Book Now!')
			->nest('content', 'booking.edit', array(
				'group' => $group,
				'attendees' => $attendees,
				'activities' => $this->activities,
			));
	}

	/**
	 * PUT /booking/(:num?)
	 *
	 * @param integer $id
	 * @return Laravel\Response|Laravel\View
	 */
	public function put_index($id = null)
	{
		$attendees = array();
		$data = Input::all();

		if ( ! is_null($id))
		{
			$data['id'] = $id; // Override submitted id if there's an id in the path
		}

		/////////////////////////////////////////////////////////////
		// Process the Group
		//
		$group = Group::findOrCreate(Arr::get($data, 'id'));

		$errors = $group->validate($data);
		if (count($errors->all()) > 0)
		{
			// Got some errors, we'll use these later
		}
		elseif ($group->save() and $group->id) // The group is fine!
		{
			/////////////////////////////////////////////////////////////
			// Process the Attendees
			//
			foreach ($data['attendee'] as $n => $row)
			{
				// Ignore any rows that do not have a name or any activities
				if ( ! empty($row['name']) or ! empty($row['saturday']) or ! empty($row['sunday']))
				{
					if ($id = Arr::get($row, 'id'))
					{
						$attendee = Attendee::find($id);
					}
					else
					{
						$attendee = new Attendee(array(
							'group_id' => $group->id,
						));
					}

					$aterrors = $attendee->validate($row);
					if (count($aterrors->all()) > 0)
					{
						// Merge the attendee errors into the error object.
						$prefix = "attendee.$n.";
						foreach ($aterrors->messages as $key => $msgs)
						{
							$errors->messages["$prefix$key"] = $msgs;
							unset($aterrors->messages[$key]); // gc
						}
						$errors->messages["attendee"][0] = 'There are errors below, please check your input and try again.';
					}
					elseif ($attendee->save()) // the attendee is fine!
					{

					}
					else // Something went wrong saving $attendee
					{
						$errors->add("attendee.$n", 'Something has gone wrong, please check your input and try again.');
					}

					$attendees[$n] = $attendee;
				}
				else
				{
					$attendees[$n] = new Attendee(); // Blank attendee
				}
			}
		}
		elseif (count($errors->all()) == 0)
		{
			// Something has gone wrong but there are no errors about it.
			$errors->add('group', 'Something has gone wrong, please check your input and try again.');
		}

		// If there were errors then let's go back to the user...
		if (count($errors->all()) > 0)
		{
			return View::make('master')
				->with('title', 'Book Now!')
				->nest('content', 'booking.edit', array(
				'group'      => $group,
				'attendees'  => $attendees,
				'activities' => $this->activities,
				'errors'     => $errors,
			));
		}
		else
		{
			$this->send_mail($group);

			return Redirect::to('booking/'.$group->id)
				->with('group_id', $group->id);
		}
	}

	/**
	 * GET /booking/(:num)
	 *
	 * @param integer $id
	 * @return Laravel\View
	 */
	public function get_index($id = null)
	{
		if (is_null($id))
		{
			// What do we want to do here, list user's groups, redirect to user's first group?
			return Redirect::to('booking/new');
		}
		else if ($id == Session::get('group_id'))
		{
			$group = Group::find($id);

			if ($group)
			{
				return View::make('master')
					->with('title', 'Thank-you')
					->nest('content', 'booking.thank-you', array(
						'group'      => $group,
						'attendees'  => $attendees,
					));
			}
		}
		// Group does not exist
		return Response::error(404);
	}

	/**
	 * Sends an e-mail about the given group booking.
	 *
	 * @param Group $group
	 */
	protected function send_mail(Group $group)
	{
		$text = View::make('booking.email.text.thank-you', array(
			'group' => $group,
		));
		$html = View::make('booking.email.html.thank-you', array(
			'group' => $group,
		));

		$mail = new PHPMailer();
		$mail->SetFrom('womble@leicestershirescouts.org.uk', 'Womble');
		$mail->AddBCC('womble@leicestershirescouts.org.uk', 'Womble');
		$mail->Subject = 'Womble - Booking #'.$group->id;
		$mail->IsHTML(true);
		$mail->Body    = $html->render();
		$mail->AltBody = $text->render();

		$mail->AddAddress($group->email, $group->contact);

		return $mail->Send();
	}

}
