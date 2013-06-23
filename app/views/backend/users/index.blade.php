@extends('backend/layouts/default')

{{-- Page title --}}
@section('title')
{{ trans("admin/users/general.title") }} ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		{{ trans("admin/users/general.title") }}

		<div class="pull-right">
			<a href="{{ route('user/create') }}" class="btn btn-large btn-link"><i class="icon-plus-sign icon-white"></i> {{ trans('button.create') }}</a>
		</div>
	</h3>
</div>

{{ $users->links() }}

<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th class="span2">{{ trans('admin/users/table.first_name') }}</th>
			<th class="span2">{{ trans('admin/users/table.last_name') }}</th>
			<th class="span2">{{ trans('admin/users/table.email') }}</th>
			<th class="span2">{{ trans('admin/users/table.activated') }}</th>
			<th class="span2">{{ trans('admin/users/table.created_at') }}</th>
			<th class="span2">{{ trans('table.actions') }}</th>
		</tr>
	</thead>
	<tbody>
		@if ($users->count() >= 1)
		@foreach ($users as $user)
		<tr>
			<td>{{ $user->first_name }}</td>
			<td>{{ $user->last_name }}</td>
			<td>{{ $user->email }}</td>
			<td>{{ trans('general.' . ($user->isActivated() ? 'yes' : 'no')) }}</td>
			<td>{{ $user->created_at->diffForHumans() }}</td>
			<td>
				<a href="{{ route('user/update', $user->id) }}" class="btn btn-link tip" title="{{ trans('button.edit') }}">
					<span class="icon-stack">
						<i class="icon-check-empty icon-stack-base"></i>
						<i class="icon-pencil"></i>
					</span>
				</a>
				@if ( ! is_null($user->deleted_at))
				<a href="{{ route('user/restore', $user->id) }}" class="btn btn-link tip" title="{{ trans('button.delete') }}">
					<span class="icon-stack">
						<i class="icon-check-empty icon-stack-base"></i>
						<i class="icon-trash"></i>
					</span>
				</a>
				@elseif (Sentry::getId() !== $user->id)
				<a href="{{ route('user/delete', $user->id) }}" class="btn btn-link tip" title="{{ trans('button.delete') }}">
					<span class="icon-stack">
						<i class="icon-check-empty icon-stack-base"></i>
						<i class="icon-trash"></i>
					</span>
				</a>
				@endif
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

{{ $users->links() }}
@stop
