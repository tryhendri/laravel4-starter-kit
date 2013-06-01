@extends('frontend/layouts/default')

{{-- Page content --}}
@section('content')
@foreach ($articles as $article)
<div class="row">
	<div class="span8">
		<!-- Post Title -->
		<div class="row">
			<div class="span8">
				<h4><strong><a href="{{ $article->url() }}">{{{ $article->title }}}</a></strong></h4>
			</div>
		</div>

		<!-- Post Content -->
		<div class="row">
			<div class="span2">
				<a href="{{ $article->url() }}" class="thumbnail"><img src="{{ $article->thumbnail() }}" alt=""></a>
			</div>
			<div class="span6">
				<p>
					{{{ Str::limit($article->content, 200) }}}
				</p>
				<p><a class="btn btn-mini" href="{{ $article->url() }}">Read more...</a></p>
			</div>
		</div>

		<!-- Post Footer -->
		<div class="row">
			<div class="span8">
				<p></p>
				<p>
					<i class="icon-user"></i> by <span class="muted">{{ $article->author->first_name }}</span>
					| <i class="icon-calendar"></i> {{ $article->created_at->diffForHumans() }}
					| <i class="icon-comment"></i> <a href="{{ $article->url() }}#comments">{{ $article->comments->count() }} Comments</a>
				</p>
			</div>
		</div>
	</div>
</div>

<hr />
@endforeach

{{ $articles->links() }}
@stop
