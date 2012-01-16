@section('breadcrumbs')
<nav class="container"><ul class="breadcrumb">
	<li>{{HTML::link('/', 'Home')}} <span class="divider">/</span></li>
	<li>{{HTML::link('admin', 'Admin')}} <span class="divider">/</span></li>
	<li>{{HTML::link('admin/user', 'Users')}} <span class="divider">/</span></li>
	<li>{{HTML::link('admin/user/'.$user->id, $user->name)}} <span class="divider">/</span></li>
	<li class="active">Edit</li>
</ul></nav>
@endsection


<article>
	<h1>{{$user->name}}</h1>

	@if($user->id)
	<h2>Edit User</h2>
	{{Form::open(URL::to('admin/user/'.$user->id), 'PUT', array('class' => Form::TYPE_HORIZONTAL))}}
	<?php $cancel = HTML::link('admin/user/'.$user->id, 'Cancel', array('class' => 'btn cancel')) ?>
	@else
	<h2>Create User</h2>
	{{Form::open(URL::to('admin/user'), 'POST', array('class' => Form::TYPE_HORIZONTAL))}}
	<?php $cancel = HTML::link('admin/user', 'Cancel', array('class' => 'btn cancel')) ?>
	@endif
	{{Form::token()}}

	{{Form::field('text', 'name', 'Name', array(Input::get('name', $user->name)), array('error' => $errors->first('name')))}}
	{{Form::field('email', 'email', 'E-mail Address', array(Input::get('email', $user->email)), array('error' => $errors->first('email')))}}
	<?php echo Form::field_list('Roles', array(
		Form::labelled_checkbox('admin', 'Admin', 1, $user->admin == 1), // @todo disable changing this for self
	)); ?>

	{{Form::actions(array(Form::submit('Save', array('class' => 'primary')), $cancel))}}
	{{Form::close()}}

</article>

