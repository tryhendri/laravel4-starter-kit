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
			<a href="{{ route('article/create') }}" class="btn btn-large btn-link"><i class="icon-plus-sign icon-white"></i> {{ trans('button.create') }}</a>
		</div>
	</h3>
</div>

{{ $articles->links() }}

<table class="table table-striped table-hover">
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
			<td><a href="{{ route('article/comments', $article->id) }}">{{ $article->comments->count() }}</a></td>
			<td>{{ $article->created_at->diffForHumans() }}</td>
			<td>
				<a href="{{ route('article/update', $article->id) }}" class="btn btn-link tip" title="{{ trans('button.edit') }}">
					<span class="icon-stack">
						<i class="icon-check-empty icon-stack-base"></i>
						<i class="icon-pencil"></i>
					</span>
				</a>
				<a href="{{ route('article/copy', $article->id) }}" class="btn btn-link tip" title="{{ trans('button.copy') }}">
					<span class="icon-stack">
						<i class="icon-check-empty icon-stack-base"></i>
						<i class="icon-copy"></i>
					</span>
				</a>
				<a href="{{ route('article/delete', $article->id) }}" class="btn btn-link tip" title="{{ trans('button.delete') }}">
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

{{ $articles->links() }}
@stop
