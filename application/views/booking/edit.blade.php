<article>
	<h1>Book Now!</h1>

@if ($group->id)
	{{Form::open(URL::to('booking/'.$group->id), 'PUT', array('class' => Form::TYPE_HORIZONTAL.' booking'))}}
	{{Form::hidden('id', $group->id)}}
@else
	{{Form::open(URL::to('booking'), 'PUT', array('class' => Form::TYPE_HORIZONTAL.' booking'))}}
@endif
	{{Form::token()}}

	<h2>Group Details</h2>

	{{Form::field('text', 'name', 'Group Name *', array(Input::get('name', $group->name)), array('error' => $errors->first('name')))}}
	{{Form::field('text', 'district', 'District *', array(Input::get('district', $group->district)), array('error' => $errors->first('district')))}}
	{{Form::field('text', 'contact', 'Main Contact *', array(Input::get('contact', $group->contact)), array('error' => $errors->first('contact')))}}
	{{Form::field('email', 'email', 'E-mail Address *', array(Input::get('email', $group->email)), array('error' => $errors->first('email')))}}
	{{Form::field('telephone', 'phone', 'Phone Number', array(Input::get('phone', $group->phone)), array('error' => $errors->first('phone')))}}

	<h2>Attendees</h2>

	{{$errors->first('attendee', '<p class="error-text">:message</p>')}}

	<p class="help-text">Please include in the special requirements any medical conditions that may affect you during
		the event, any food allergies or dietary requirements.</p>

	<table class="table attendees">
		<thead><tr>
			<th><!-- ID --></th>
			<th>Name *</th>
			<th>Saturday *</th>
			<th>Sunday *</th>
			<th>Special Requirements</th>
		</tr></thead>
		<tbody>
	@for ($i = 0; $i < 12; $i++)
			<tr>
				<th scope="row">{{$i+1}}
@if ($attendees[$i]->id)
				{{Form::hidden("attendee[$i][id]", $attendees[$i]->id)}}
@endif
				</th>
				<td class="control-group{{$errors->has("attendee.$i.name")?' error':''}}">{{Form::text("attendee[$i][name]", Input::get("attendee.$i.name", $attendees[$i]->name))}}</td>
				<td class="control-group{{$errors->has("attendee.$i.saturday")?' error':''}}">{{Form::select("attendee[$i][saturday]", $activities, Input::get("attendee.$i.saturday", $attendees[$i]->saturday))}}</td>
				<td class="control-group{{$errors->has("attendee.$i.sunday")?' error':''}}">{{Form::select("attendee[$i][sunday]", $activities, Input::get("attendee.$i.sunday", $attendees[$i]->sunday))}}</td>
				<td class="control-group{{$errors->has("attendee.$i.extra")?' error':''}}">{{Form::text("attendee[$i][extra]", Input::get("attendee.$i.extra", $attendees[$i]->extra), array('class' => 'special'))}}</td>
			</tr>
		{{$errors->first("attendee.$i", '<tr><td></td><td colspan="4">:message</td></tr>');}}
	@endfor
		</tbody>
	</table>

	<p class="help-text">* Fields marked with a star are required.</p>

	{{Form::actions(Form::submit('Book now', array('class' => 'primary')))}}
	{{Form::close()}}
</article>
