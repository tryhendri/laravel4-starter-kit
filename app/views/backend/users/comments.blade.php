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
	</h3>
</div>

{{-- Tabs --}}
<ul class="nav nav-tabs">
	<li><a href="{{ route('user/update', $user->id) }}">{{ trans('admin/users/general.tabs.general') }}</a></li>
	<li><a href="{{ route('user/update', $user->id) }}">{{ trans('admin/users/general.tabs.permissions') }}</a></li>
	<li class="active"><a href="#tab-permissions">{{ trans('admin/users/general.tabs.comments') }}</a></li>
</ul>

{{ $comments->links() }}

<table class="table table-striped table-hover">
	<thead>
		<tr>
			<th>{{ trans('admin/articles/table.title') }}</th>
			<th class="span2">{{ trans('admin/articles/table.created_at') }}</th>
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
				<a href="{{ route('comment/update', $comment->id) }}" class="unstyled tip" title="{{ trans('button.edit') }}">
					<span class="icon-stack">
						<i class="icon-check-empty icon-stack-base"></i>
						<i class="icon-pencil"></i>
					</span>
				</a>
				<a href="{{ route('comment/delete', $comment->id) }}" class="unstyled tip" title="{{ trans('button.delete') }}">
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
			<td colspan="6">{{ trans('table.no_results') }}</td>
		</tr>
		@endif
	</tbody>
</table>

{{ $comments->links() }}

@stop
