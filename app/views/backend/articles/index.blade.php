@extends('backend/layouts/default')

{{-- Page title --}}
@section('title')
{{ trans("admin/articles/general.title") }} ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		{{ trans("admin/articles/general.title") }}

		<div class="pull-right">
			<a href="{{ route('create/article') }}" class="btn btn-small btn-info"><i class="icon-plus-sign icon-white"></i> {{ trans('button.create') }}</a>
		</div>
	</h3>
</div>

{{ $articles->links() }}

<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th class="span6">{{ trans('admin/articles/table.title') }}</th>
			<th class="span2">{{ trans('admin/articles/table.comments') }}</th>
			<th class="span2">{{ trans('admin/articles/table.created_at') }}</th>
			<th class="span2">{{ trans('table.actions') }}</th>
		</tr>
	</thead>
	<tbody>
		@if ($articles->count() >= 1)
		@foreach ($articles as $article)
		<tr>
			<td>{{ $article->title }}</td>
			<td>{{ $article->comments->count() }}</td>
			<td>{{ $article->created_at->diffForHumans() }}</td>
			<td>
				<a href="{{ route('update/article', $article->id) }}" class="btn btn-mini tip" title="{{ trans('button.edit') }}">{{ trans('button.edit') }}</a>
				<a href="{{ route('copy/article', $article->id) }}" class="btn btn-mini btn-info tip" title="{{ trans('button.copy') }}">{{ trans('button.copy') }}</a>
				<a href="{{ route('delete/article', $article->id) }}" class="btn btn-mini btn-danger tip" title="{{ trans('button.delete') }}">{{ trans('button.delete') }}</a>
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

{{ $articles->links() }}
@stop
