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

<div class="row">
@foreach ($comments as $comment)

	<div class="span12">
		<div class="row">
			<div class="span1">
				<img class="thumbnail" src="{{ $comment->author->gravatar() }}">
			</div>
			<div class="span11">
				<p>
					<span class="muted">#{{ $comment->id }}</span>

					<strong>{{ $comment->author->fullName() }}</strong>

					<span class="tip" title="{{ $comment->created_at }}">&bull; {{ $comment->created_at->diffForHumans() }}</span>

					<span class="pull-right">
						<span class="icon-stack"><a href="{{ route('comment/update', array('id' => $comment->article_id, 'cid' => $comment->id)) }}" class="unstyled">

								<i class="icon-check-empty icon-stack-base"></i>
								<i class="icon-pencil"></i>

						</a></span>
						<a href="{{ route('comment/delete', array('id' => $comment->article_id, 'cid' => $comment->id)) }}" class="unstyled">
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
			</div>
		</div>
	</div>

@endforeach
</div>

{{ $comments->links() }}
@else
<div class="hero-unit">
	<p class="text-center">{{ trans('table.no_results') }}</p>
</div>
@endif
@stop
