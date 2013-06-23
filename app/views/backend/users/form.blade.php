@extends('backend/layouts/default')

{{-- Page title --}}
@section('title')
{{ trans("admin/users/general.{$pageSegment}.title") }} ::
@parent
@stop

{{-- Page content --}}
@section('content')
<div class="page-header">
	<h3>
		{{ trans("admin/users/general.{$pageSegment}.title") }}

		<div class="pull-right">
			<a href="{{ route('users') }}" class="btn btn-large btn-link"><i class="icon-circle-arrow-left icon-white"></i> {{ trans('button.back') }}</a>
		</div>
	</h3>
</div>

{{-- Tabs --}}
<ul class="nav nav-tabs">
	<li class="active"><a href="#tab-general" data-toggle="tab">{{ trans('admin/users/general.tabs.general') }}</a></li>
	<li><a href="#tab-permissions" data-toggle="tab">{{ trans('admin/users/general.tabs.permissions') }}</a></li>
	@if ( ! empty($user))
	<li><a href="{{ route('user/comments', $user->id) }}">{{ trans('admin/users/general.tabs.comments') }}</a></li>
	@endif
</ul>

<form class="form-horizontal" method="post" action="" autocomplete="off">
	{{-- CSRF Token --}}
	<input type="hidden" name="_token" value="{{ csrf_token() }}" />

	{{-- Tabs Content --}}
	<div class="tab-content">
		{{-- General tab --}}
		<div class="tab-pane active" id="tab-general">
			{{-- First Name --}}
			<div class="control-group{{ $errors->has('first_name') ? ' error' : null }}">
				<label class="control-label" for="first_name">{{ trans('admin/users/form.first_name') }}</label>
				<div class="controls">
					<input type="text" name="first_name" id="first_name" value="{{{ Input::old('first_name', ! empty($user) ? $user->first_name : null) }}}" />
					{{ $errors->first('first_name', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			{{-- Last Name --}}
			<div class="control-group{{ $errors->has('last_name') ? ' error' : null }}">
				<label class="control-label" for="last_name">{{ trans('admin/users/form.last_name') }}</label>
				<div class="controls">
					<input type="text" name="last_name" id="last_name" value="{{{ Input::old('last_name', ! empty($user) ? $user->last_name : null) }}}" />
					{{ $errors->first('last_name', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			{{-- Email --}}
			<div class="control-group{{ $errors->has('email') ? ' error' : null }}">
				<label class="control-label" for="email">{{ trans('admin/users/form.email') }}</label>
				<div class="controls">
					<input type="text" name="email" id="email" value="{{{ Input::old('email', ! empty($user) ? $user->email : null) }}}" />
					{{ $errors->first('email', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			{{-- Password --}}
			<div class="control-group{{ $errors->has('password') ? ' error' : null }}">
				<label class="control-label" for="password">{{ trans('admin/users/form.password') }}</label>
				<div class="controls">
					<input type="password" name="password" id="password" value="" />
					{{ $errors->first('password', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			{{-- Password Confirm --}}
			<div class="control-group{{ $errors->has('password_confirm') ? ' error' : null }}">
				<label class="control-label" for="password_confirm">{{ trans('admin/users/form.password_confirm') }}</label>
				<div class="controls">
					<input type="password" name="password_confirm" id="password_confirm" value="" />
					{{ $errors->first('password_confirm', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			{{-- Activation Status --}}
			<div class="control-group{{ $errors->has('activated') ? ' error' : null }}">
				<label class="control-label" for="activated">{{ trans('admin/users/form.activated') }}</label>
				<div class="controls">
					<select{{ ! empty($user) ? $user->id : null === Sentry::getId() ? ' disabled="disabled"' : null }} name="activated" id="activated">
						<option value="1"{{ Input::old('activated', ! empty($user) ? (int) $user->isActivated() : 1) === 1 ? ' selected="selected"' : null }}>{{ trans('general.yes') }}</option>
						<option value="0"{{ Input::old('activated', ! empty($user) ? (int) $user->isActivated() : 1) === 0 ? ' selected="selected"' : null }}>{{ trans('general.no') }}</option>
					</select>
					{{ $errors->first('activated', '<span class="help-inline">:message</span>') }}
				</div>
			</div>

			{{-- Groups --}}
			<div class="control-group{{ $errors->has('groups') ? ' error' : null }}">
				<label class="control-label" for="groups">{{ trans('admin/users/form.groups') }}</label>
				<div class="controls">
					<select name="groups[]" id="groups[]" multiple>
						@foreach ($groups as $group)
						<option value="{{ $group->id }}"{{ (array_key_exists($group->id, $userGroups) ? ' selected="selected"' : null) }}>{{ $group->name }}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>

		{{-- Permissions tab --}}
		<div class="tab-pane" id="tab-permissions">
			<div class="controls">
				<div class="control-group">

					@foreach ($permissions as $area => $permissions)
					<fieldset>
						<legend>{{ $area }}</legend>

						@foreach ($permissions as $permission)
						<div class="control-group">
							<label class="control-group">{{ $permission['label'] }}</label>

							<div class="radio inline">
								<label for="{{ $permission['permission'] }}_allow" onclick="">
									<input type="radio" value="1" id="{{ $permission['permission'] }}_allow" name="permissions[{{ $permission['permission'] }}]"{{ (array_get($userPermissions, $permission['permission']) === 1 ? ' checked="checked"' : null) }}>
									{{ trans('general.allow') }}
								</label>
							</div>

							<div class="radio inline">
								<label for="{{ $permission['permission'] }}_deny" onclick="">
									<input type="radio" value="-1" id="{{ $permission['permission'] }}_deny" name="permissions[{{ $permission['permission'] }}]"{{ (array_get($userPermissions, $permission['permission']) === -1 ? ' checked="checked"' : null) }}>
									{{ trans('general.deny') }}
								</label>
							</div>

							@if ($permission['can_inherit'])
							<div class="radio inline">
								<label for="{{ $permission['permission'] }}_inherit" onclick="">
									<input type="radio" value="0" id="{{ $permission['permission'] }}_inherit" name="permissions[{{ $permission['permission'] }}]"{{ ( ! array_get($userPermissions, $permission['permission']) ? ' checked="checked"' : null) }}>
									{{ trans('general.inherit') }}
								</label>
							</div>
							@endif
						</div>
						@endforeach

					</fieldset>
					@endforeach

				</div>
			</div>
		</div>
	</div>

	{{-- Form Actions --}}
	<div class="control-group">
		<div class="controls">
			<a class="btn btn-link" href="{{ route('users') }}">{{ trans('button.cancel') }}</a>

			<button type="reset" class="btn">{{ trans('button.reset') }}</button>

			<button type="submit" class="btn btn-success">{{ trans('button.update') }}</button>
		</div>
	</div>
</form>
@stop
