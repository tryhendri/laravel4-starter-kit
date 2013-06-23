@extends('backend/layouts/default')


@section('content')

<div class="page-header">
	<h3>
		Article Comments <small><em>in</em> {{ $article->title }}</small>

		<div class="pull-right">
			<a href="{{ route('article/update', $article->id) }}" class="btn btn-large btn-link unstyled tip"><i class="icon-circle-arrow-left icon-white"></i> Back to the Article</a>
		</div>
	</h3>
</div>

@if ( ! empty($comments))
{{ $comments->links() }}

<table class="table table-hover unstyled">
@foreach ($comments as $comment)
	<tr>
		<td class="span1">
			<img class="thumbnail" src="{{ $comment->author->gravatar() }}">
		</td>
		<td class="span11">
			<p>
				<span class="muted">#{{ $comment->id }}</span>

				<strong>{{ $comment->author->fullName() }}</strong>

				<span class="tip" title="{{ $comment->created_at }}">&bull; {{ $comment->created_at->diffForHumans() }}</span>

				<span class="pull-right">
					<a href="{{ route('comment/update', array('id' => $comment->article_id, 'cid' => $comment->id)) }}" class="unstyled tip" title="{{ trans('button.update') }}">
						<span class="icon-stack">
							<i class="icon-check-empty icon-stack-base"></i>
							<i class="icon-pencil"></i>
						</span>
					</a>
					<a href="{{ route('comment/delete', array('id' => $comment->article_id, 'cid' => $comment->id)) }}" class="unstyled tip" title="{{ trans('button.delete') }}">
						<span class="icon-stack">
							<i class="icon-check-empty icon-stack-base"></i>
							<i class="icon-trash"></i>
						</span>
					</a>
				</span>
			</p>

			<p>
				{{{ Str::limit($comment->content, 300) }}}
			</p>
		</td>
	</tr>
@endforeach
</table>

{{ $comments->links() }}
@else
<div class="hero-unit">
	<p class="text-center">{{ trans('table.no_results') }}</p>
</div>
@endif
@stop
