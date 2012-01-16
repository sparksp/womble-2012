@section('breadcrumbs')
<nav class="container"><ul class="breadcrumb">
	<li>{{HTML::link('/', 'Home')}} <span class="divider">/</span></li>
	<li>{{HTML::link('admin', 'Admin')}} <span class="divider">/</span></li>
	<li>{{HTML::link('admin/user', 'Users')}} <span class="divider">/</span></li>
	<li>{{HTML::link('admin/user/'.$user->id, $user->name)}} <span class="divider">/</span></li>
	<li class="active">Disable</li>
</ul></nav>
@endsection


<article>
	<h1>{{HTML::entities($user->name)}}</h1>

	<h2>Disable User</h2>
	{{Form::open(URL::to('admin/user/'.$user->id.'/password'), 'DELETE', array('class' => 'delete user'))}}
	{{Form::token()}}

	<p>Are you sure you want to disable <strong>{{HTML::entities($user->name)}}</strong>?</p>

	<?php echo Form::actions(array(
		Form::submit('Disable', array('class' => 'danger')),
		HTML::link('admin/user/'.$user->id, 'Cancel', array('class' => 'btn')),
	)); ?>

	<?=Form::close()?>

</article>

