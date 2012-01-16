@section('breadcrumbs')
<nav class="container"><ul class="breadcrumb">
	<li>{{HTML::link('/', 'Home')}} <span class="divider">/</span></li>
	<li>{{HTML::link('admin', 'Admin')}} <span class="divider">/</span></li>
	<li class="active">Users</li>
</ul></nav>
@endsection

<h1>User Admin</h1>

@if (count($content->results))
<table>
	<thead><tr>
		<th>User</th>
		<th>Last seen</th>
		<th>Tools</th>
	</tr></thead>
	<tbody>
	@foreach ($content->results as $user)
		<tr>
			<td>{{HTML::entities($user->name)}}</td>
			<td>{{strftime('%c', strtotime($user->seen_at))}}</td>
			<td class="btn-group">
				{{HTML::link('admin/user/'.$user->id.'/edit', 'Edit', array('class' => 'btn'))}}
			@if ($user->id !== Auth::user()->id)
				{{HTML::link('admin/user/'.$user->id.'/disable', 'Disable', array('class' => 'btn'))}}
			@endif
			</td>
		</tr>
	@endforeach
	</tbody>
</table>
	{{$content->links()}}
@else
	<p>
		No users found.
	</p>
@endif