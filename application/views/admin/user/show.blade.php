@section('breadcrumbs')
<nav class="container"><ul class="breadcrumb">
	<li>{{HTML::link('/', 'Home')}} <span class="divider">/</span></li>
	<li>{{HTML::link('admin', 'Admin')}} <span class="divider">/</span></li>
	<li>{{HTML::link('admin/user', 'Users')}} <span class="divider">/</span></li>
	<li class="active">{{HTML::entities($user->name)}}</li>
</ul></nav>
@endsection


<article>
	<h1>{{$user->name}}</h1>

	<nav class="actions btn-group">
		{{HTML::link('admin/user/'.$user->id.'/edit', 'Edit', array('class' => 'btn edit', 'title' => 'Delete user'))}}
	@if ($user->id !== Auth::user()->id)
		{{HTML::link('admin/user/'.$user->id.'/disable', 'Disable', array('class' => 'btn disable', 'title' => 'Disable user'))}}
	@endif
	</nav>

</article>

