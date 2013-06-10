@extends('backend/layouts/default')


@section('content')

<div class="page-header">
	<h3>
		{{ $article->title }}

		<div class="pull-right">
			<a href="{{ route('article/update', $article->id) }}" class="btn btn-small btn-info"><i class="icon-circle-arrow-left icon-white"></i> Back to the Article</a>
		</div>
	</h3>
</div>

@if ( ! empty($comments))
{{ $comments->links() }}

@foreach ($comments as $comment)
<div class="well clearfix">
	<div class="span11">
		<div class="row">
			<div class="span1">
				<img class="thumbnail" src="{{ $comment->author->gravatar() }}">
			</div>
			<div class="span10">
				<p>
					<span class="muted">#{{ $comment->id }}</span>

					<strong>{{ $comment->author->fullName() }}</strong>

					<span class="tip" title="{{ $comment->created_at }}">&bull; {{ $comment->created_at->diffForHumans() }}</span>

					<span class="pull-right">
						<a href="{{ route('comment/update', array('id' => $comment->article_id, 'cid' => $comment->id)) }}" class="btn btn-mini">Edit</a>
						<a href="{{ route('comment/delete', array('id' => $comment->article_id, 'cid' => $comment->id)) }}" class="btn btn-mini btn-danger">Delete</a>
					</span>
				</p>

				<p>
					{{{ Str::limit($comment->content, 300) }}}
				</p>
			</div>
		</div>
	</div>
</div>
@endforeach

{{ $comments->links() }}
@else
<div class="hero-unit">
	<p class="text-center">{{ trans('table.no_results') }}</p>
</div>
@endif
@stop
