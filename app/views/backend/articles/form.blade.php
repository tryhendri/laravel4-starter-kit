@extends('backend/layouts/default')

{{-- Page title --}}
@section('title')
{{ trans("admin/articles/general.{$pageSegment}.title") }} ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		{{ trans("admin/articles/general.{$pageSegment}.title") }}

		<div class="pull-right">
			<a href="{{ route('articles') }}" class="btn btn-small btn-inverse"><i class="icon-circle-arrow-left icon-white"></i> {{ trans('button.back') }}</a>
		</div>
	</h3>
</div>

<!-- Tabs -->
<ul class="nav nav-tabs">
	<li class="active"><a href="#tab-general" data-toggle="tab">{{ trans('admin/articles/general.tabs.general') }}</a></li>
	<li><a href="#tab-meta-data" data-toggle="tab">{{ trans('admin/articles/general.tabs.metadata') }}</a></li>
	@if ( ! empty($article) and $pageSegment == 'update')
	<li><a href="{{ route('article/comments', $article->id) }}">{{ trans('admin/articles/general.tabs.comments') }}</a></li>
	@endif
</ul>

<form class="form-horizontal" method="post" action="" autocomplete="off">
	<!-- CSRF Token -->
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />

	<!-- Tabs Content -->
	<div class="tab-content">
		<!-- General tab -->
		<div class="tab-pane active" id="tab-general">
			<!-- Article Title -->
			<div class="control-group{{ $errors->has('title') ? ' error' : '' }}">
				<label class="control-label" for="title">{{ trans('admin/articles/form.title') }}</label>
				<div class="controls">
					<input type="text" name="title" id="title" value="{{{ Input::old('title', ! empty($article) ? $article->title : null) }}}" />
					{{ $errors->first('title', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Article Slug -->
			<div class="control-group{{ $errors->has('slug') ? ' error' : '' }}">
				<label class="control-label" for="slug">{{ trans('admin/articles/form.slug') }}</label>
				<div class="controls">
					<div class="input-prepend">
						<span class="add-on">
							{{ str_finish(URL::to('/'), '/') }}
						</span>
						<input class="span6" type="text" name="slug" id="slug" value="{{{ Input::old('slug', ! empty($article) ? $article->slug : null) }}}" />
					</div>
					{{ $errors->first('slug', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Content -->
			<div class="control-group{{ $errors->has('content') ? ' error' : '' }}">
				<label class="control-label" for="content">{{ trans('admin/articles/form.content') }}</label>
				<div class="controls">
					<textarea class="span10" name="content" value="content" rows="10">{{{ Input::old('content', ! empty($article) ? $article->content : null) }}}</textarea>
					{{ $errors->first('content', '<span class="help-inline">:message</span>') }}
				</div>
			</div>
		</div>

		<!-- Meta Data tab -->
		<div class="tab-pane" id="tab-meta-data">
			<!-- Meta Title -->
			<div class="control-group{{ $errors->has('meta-title') ? ' error' : '' }}">
				<label class="control-label" for="meta-title">{{ trans('admin/articles/form.meta.title') }}</label>
				<div class="controls">
					<input class="span10" type="text" name="meta-title" id="meta-title" value="{{{ Input::old('meta-title', ! empty($article) ? $article->meta_title : null) }}}" />
					{{ $errors->first('meta-title', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Meta Description -->
			<div class="control-group{{ $errors->has('meta-description') ? ' error' : '' }}">
				<label class="control-label" for="meta-description">{{ trans('admin/articles/form.meta.description') }}</label>
				<div class="controls">
					<input class="span10" type="text" name="meta-description" id="meta-description" value="{{{ Input::old('meta-description', ! empty($article) ? $article->meta_description : null) }}}" />
					{{ $errors->first('meta-description', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			<!-- Meta Keywords -->
			<div class="control-group{{ $errors->has('meta-keywords') ? ' error' : '' }}">
				<label class="control-label" for="meta-keywords">{{ trans('admin/articles/form.meta.keywords') }}</label>
				<div class="controls">
					<input class="span10" type="text" name="meta-keywords" id="meta-keywords" value="{{{ Input::old('meta-keywords', ! empty($article) ? $article->meta_keywords : null) }}}" />
					{{ $errors->first('meta-keywords', '<span class="help-inline">:message</span>') }}
				</div>
			</div>
		</div>
	</div>

	<!-- Form Actions -->
	<div class="control-group">
		<div class="controls">
			<a class="btn btn-link" href="{{ route('articles') }}">{{ trans('button.cancel') }}</a>

			<button type="reset" class="btn">{{ trans('button.reset') }}</button>

			<button type="submit" class="btn btn-success">{{ trans('button.publish') }}</button>
		</div>
	</div>
</form>
@stop
