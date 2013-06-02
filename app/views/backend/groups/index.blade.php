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
			<a href="{{ route('create/group') }}" class="btn btn-small btn-info"><i class="icon-plus-sign icon-white"></i> {{ trans('button.create') }}</a>
		</div>
	</h3>
</div>

{{ $groups->links() }}

<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th class="span1">{{ trans('admin/groups/table.id') }}</th>
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
			<td>{{ $group->id }}</td>
			<td>{{ $group->name }}</td>
			<td>{{ $group->users()->count() }}</td>
			<td>{{ $group->created_at->diffForHumans() }}</td>
			<td>
				<a href="{{ route('update/group', $group->id) }}" class="btn btn-mini tip" title="{{ trans('button.edit') }}">{{ trans('button.edit') }}</a>
				<a href="{{ route('delete/group', $group->id) }}" class="btn btn-mini btn-danger tip" title="{{ trans('button.delete') }}">{{ trans('button.delete') }}</a>
			</td>
		</tr>
		@endforeach
		@else
		<tr>
			<td colspan="5">{{ trans('table.no_results') }}</td>
		</tr>
		@endif
	</tbody>
</table>

{{ $groups->links() }}
@stop
