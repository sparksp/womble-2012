@section('breadcrumbs')
<nav class="container"><ul class="breadcrumb">
	<li>{{HTML::link('/', 'Home')}} <span class="divider">/</span></li>
	<li class="active">Admin</li>
</ul></nav>
@endsection


<h1>Admin</h1>

<ul>
	<li>{{HTML::link('admin/user', 'Users')}}</li>
</ul>