@extends('backend/layouts/default')

@section('content')

<div class="row">

	<div class="span4">

		<h3>
			Latest Users
			<small class="pull-right"><a href="{{ route('users') }}">View all</a></small>
		</h3>

		<table class="table table-bordered table-hover">
			<tbody>
				@foreach ($users as $user)
				<tr>
					<td>
						<a href="{{ route('user/update', $user->id) }}">{{ $user->fullName() }}</a>

						<span class="pull-right">
							<i class="icon-envelope tip" title="{{ $user->email }}"></i>
							<i class="icon-time tip" title="{{ $user->created_at }}"></i>
						</span>
					</td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
	<div class="span8">

		<h3>
			Latest Comments
			<small class="pull-right"><a href="{{ route('articles/comments') }}">View all</a></small>
		</h3>

		<table class="table table-bordered table-hover">
			<thead>
				<tr>
					<th class="span6">Article</th>
					<th class="span3">Comment by</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($comments as $comment)
				<tr>
					<td>
						<a href="{{ route('article/update', $comment->article_id) }}">{{ $comment->article->title }}</a>

						<span class="pull-right">
							<i class="icon-time tip" title="{{ $comment->created_at }}"></i>
						</span>
					</td>
					<td><a href="{{ route('user/update', $comment->user_id) }}">{{ $comment->author->fullName() }}</a></td>
				</tr>
				@endforeach
			</tbody>
		</table>

	</div>
</div>

@stop
