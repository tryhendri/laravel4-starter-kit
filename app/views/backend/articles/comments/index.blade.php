@extends('backend/layouts/default')

{{-- Page title --}}
@section('title')
{{ trans("admin/articles/comments/general.title") }} ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		{{ trans("admin/articles/comments/general.title") }}


	</h3>
</div>

{{ $comments->links() }}

<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th class="span6">{{ trans('admin/articles/comments/table.article') }}</th>
			<th class="span2">{{ trans('admin/articles/comments/table.created_at') }}</th>
			<th class="span2">{{ trans('table.actions') }}</th>
		</tr>
	</thead>
	<tbody>
		@if ($comments->count() >= 1)
		@foreach ($comments as $comment)
		<tr>
			<td><a href="{{ route('article/update', $comment->article_id) }}">{{ $comment->article->title }}</a></td>
			<td>{{ $comment->created_at->diffForHumans() }}</td>
			<td>
				<a href="{{ route('comment/update', $comment->id) }}" class="btn btn-mini tip" title="{{ trans('button.edit') }}">{{ trans('button.edit') }}</a>
				<a href="{{ route('comment/delete', $comment->id) }}" class="btn btn-mini btn-danger tip" title="{{ trans('button.delete') }}">{{ trans('button.delete') }}</a>
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

{{ $comments->links() }}
@stop
