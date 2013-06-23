@extends('backend/layouts/default')

{{-- Web site Title --}}
@section('title')
{{ trans("admin/groups/general.title") }} ::
@parent
@stop

{{-- Content --}}
@section('content')
<div class="page-header">
	<h3>
		{{ trans("admin/groups/general.title") }}

		<div class="pull-right">
			<a href="{{ route('group/create') }}" class="btn btn-large btn-link"><i class="icon-plus-sign icon-white"></i> {{ trans('button.create') }}</a>
		</div>
	</h3>
</div>

{{ $groups->links() }}

<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th class="span6">{{ trans('admin/groups/table.name') }}</th>
			<th class="span2">{{ trans('admin/groups/table.users') }}</th>
			<th class="span2">{{ trans('admin/groups/table.created_at') }}</th>
			<th class="span2">{{ trans('table.actions') }}</th>
		</tr>
	</thead>
	<tbody>
		@if ($groups->count() >= 1)
		@foreach ($groups as $group)
		<tr>
			<td>{{ $group->name }}</td>
			<td>{{ $group->users()->count() }}</td>
			<td>{{ $group->created_at->diffForHumans() }}</td>
			<td>
				<a href="{{ route('group/update', $group->id) }}" class="unstyled tip" title="{{ trans('button.edit') }}">
					<span class="icon-stack">
						<i class="icon-check-empty icon-stack-base"></i>
						<i class="icon-pencil"></i>
					</span>
				</a>
				<a href="{{ route('group/delete', $group->id) }}" class="unstyled tip" title="{{ trans('button.delete') }}">
					<span class="icon-stack">
						<i class="icon-check-empty icon-stack-base"></i>
						<i class="icon-trash"></i>
					</span>
				</a>
			</td>
		</tr>
		@endforeach
		@else
		<tr>
			<td colspan="4">{{ trans('table.no_results') }}</td>
		</tr>
		@endif
	</tbody>
</table>

{{ $groups->links() }}
@stop
