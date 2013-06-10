@extends('backend/layouts/default')

{{-- Page title --}}
@section('title')
{{ trans('admin/users/general.comments.title') }} ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		{{ trans('admin/users/general.comments.title') }}

		<div class="pull-right">
			<a href="{{ route('users') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> {{ trans('button.back') }}</a>
		</div>
	</h3>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs">
	<li><a href="{{ route('user/update', $user->id) }}">{{ trans('admin/users/general.tabs.general') }}</a></li>
	<li><a href="{{ route('user/update', $user->id) }}">{{ trans('admin/users/general.tabs.permissions') }}</a></li>
	<li class="active"><a href="#tab-permissions">{{ trans('admin/users/general.tabs.comments') }}</a></li>
</ul>

{{ $comments->links() }}

<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th class="span2">* Article Name</th>
			<th class="span2">* Created at</th>
			<th class="span2">{{ trans('table.actions') }}</th>
		</tr>
	</thead>
	<tbody>
		@if ($comments->count() >= 1)
		@foreach ($comments as $comment)
		<tr>
			<td>{{ $comment->article->name }}</td>
			<td>{{ $comment->created_at->diffForHumans() }}</td>
			<td>

			</td>
		</tr>
		@endforeach
		@else
		<tr>
			<td colspan="6">{{ trans('table.no_results') }}</td>
		</tr>
		@endif
	</tbody>
</table>

{{ $comments->links() }}

@stop
