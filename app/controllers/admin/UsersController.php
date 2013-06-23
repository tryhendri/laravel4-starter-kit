<?php namespace Controllers\Admin;

use AdminController;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserExistsException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Config;
use Input;
use Lang;
use Redirect;
use Sentry;
use Validator;
use View;

class UsersController extends AdminController {

	/**
	 * Declare the rules for the form validation
	 *
	 * @var array
	 */
	protected $validationRules = array(
		'first_name'       => 'required|min:3',
		'last_name'        => 'required|min:3',
		'email'            => 'required|email|unique:users,email',
		'password'         => 'required|between:3,32',
		'password_confirm' => 'required|between:3,32|same:password',
	);

	/**
	 * Show a list of all the users.
	 *
	 * @return View
	 */
	public function getIndex()
	{
		// Grab all the users
		$users = Sentry::getUserProvider()->createModel()->withTrashed()->paginate();

		// Show the page
		return View::make('backend/users/index', compact('users'));
	}

	/**
	 * User create.
	 *
	 * @return View
	 */
	public function getCreate()
	{
		return $this->showForm(null, 'create');
	}

	/**
	 * User create form processing.
	 *
	 * @return Redirect
	 */
	public function postCreate()
	{
		return $this->processForm();
	}

	/**
	 * User update.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function getEdit($id = null)
	{
		return $this->showForm($id, 'update');
	}

	/**
	 * User update form processing page.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function postEdit($id = null)
	{
		return $this->processForm($id);
	}

	/**
	 * Delete the given user.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function getDelete($id = null)
	{
		try
		{
			// Get user information
			$user = Sentry::getUserProvider()->findById($id);

			// Check if we are not trying to delete ourselves
			if ($user->id === Sentry::getId())
			{
				// Prepare the error message
				$error = Lang::get('admin/users/message.error.delete');

				// Redirect to the user management page
				return Redirect::route('users')->with('error', $error);
			}

			// Do we have permission to delete this user?
			if ($user->isSuperUser() and ! Sentry::getUser()->isSuperUser())
			{
				// Redirect to the user management page
				return Redirect::route('users')->with('error', 'Insufficient permissions!');
			}

			// Delete the user
			$user->delete();

			// Prepare the success message
			$success = Lang::get('admin/users/message.success.delete');

			// Redirect to the user management page
			return Redirect::route('users')->with('success', $success);
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('admin/users/message.not_found', compact('id' ));

			// Redirect to the user management page
			return Redirect::route('users')->with('error', $error);
		}
	}

	/**
	 * Restore a deleted user.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function getRestore($id = null)
	{
		try
		{
			// Get user information
			$user = Sentry::getUserProvider()->createModel()->withTrashed()->find($id);

			// Restore the user
			$user->restore();

			// Prepare the success message
			$success = Lang::get('admin/users/message.success.restored');

			// Redirect to the user management page
			return Redirect::route('users')->with('success', $success);
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('admin/users/message.not_found', compact('id'));

			// Redirect to the user management page
			return Redirect::route('users')->with('error', $error);
		}
	}

	public function getComments($id = null)
	{
		try
		{
			// Get user information
			$user = Sentry::getUserProvider()->createModel()->withTrashed()->find($id);

			// Grab the user comments
			$comments = $user->comments()->with('article')->paginate();

			// Show the page
			return View::make('backend/users/comments', compact('user', 'comments'));
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('admin/users/message.not_found', compact('id'));

			// Redirect to the user management page
			return Redirect::route('users')->with('error', $error);
		}
	}

	/**
	 * Shows the form.
	 *
	 * @param  int     $id
	 * @param  string  $pageSegment
	 * @return mixed
	 */
	protected function showForm($id = null, $pageSegment = null)
	{
		try
		{
			// Fallback user data
			$user            = null;
			$userGroups      = array();
			$userPermissions = array();

			// Do we have the user id?
			if ( ! is_null($id))
			{
				// Get the user information
				$user = Sentry::getUserProvider()->createModel()->withTrashed()->find($id);

				// Get this user groups
				$userGroups = $user->groups()->lists('name', 'group_id');

				// Get this user permissions
				$userPermissions = array_merge(Input::old('permissions', array('superuser' => -1)), $user->getPermissions());
			}

			// Prepare the user permissions
			$this->encodePermissions($userPermissions);

			// Get a list of all the available groups
			$groups = Sentry::getGroupProvider()->findAll();

			// Get all the available permissions
			$permissions = Config::get('permissions');
			$this->encodeAllPermissions($permissions);

			// Show the page
			return View::make('backend/users/form', compact('user', 'groups', 'userGroups', 'permissions', 'userPermissions', 'pageSegment'));
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('admin/users/message.not_found', compact('id'));

			// Redirect to the user management page
			return Redirect::route('users')->with('error', $error);
		}
	}

	/**
	 * Processes the form.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	protected function processForm($id = null)
	{
		try
		{
			// We need to reverse the UI specific logic for our
			// permissions here before we update the user.
			$permissions = Input::get('permissions', array());
			$this->decodePermissions($permissions);
			app('request')->request->set('permissions', $permissions);

			if ( ! is_null($id))
			{
				// Get the user information
				$user = Sentry::getUserProvider()->findById($id);

				// Update the validation rules
				$this->validationRules['email'] = "required|email|unique:users,email,{$user->email},email";

				// Do we want to update the user password?
				if ( ! $password = Input::get('password'))
				{
					unset($this->validationRules['password']);
					unset($this->validationRules['password_confirm']);
				}
			}

			// Create a new validator instance from our validation rules
			$validator = Validator::make(Input::all(), $this->validationRules);

			// If validation fails, we'll exit the operation now.
			if ($validator->fails())
			{
				// Ooops.. something went wrong
				return Redirect::back()->withInput()->withErrors($validator);
			}

			// Update the user
			$user->first_name  = Input::get('first_name');
			$user->last_name   = Input::get('last_name');
			$user->email       = Input::get('email');
			$user->activated   = Input::get('activated', $user->activated);
			$user->permissions = Input::get('permissions');

			// Do we want to update the user password?
			if ($password)
			{
				$user->password = $password;
			}

			// Get the current user groups
			$userGroups = $user->groups()->lists('group_id', 'group_id');

			// Get the selected groups
			$selectedGroups = Input::get('groups', array());

			// Groups comparison between the groups the user currently
			// have and the groups the user wish to have.
			$groupsToAdd    = array_diff($selectedGroups, $userGroups);
			$groupsToRemove = array_diff($userGroups, $selectedGroups);

			// Assign the user to groups
			foreach ($groupsToAdd as $groupId)
			{
				$group = Sentry::getGroupProvider()->findById($groupId);

				$user->addGroup($group);
			}

			// Remove the user from groups
			foreach ($groupsToRemove as $groupId)
			{
				$group = Sentry::getGroupProvider()->findById($groupId);

				$user->removeGroup($group);
			}

			// Was the user updated?
			if ($user->save())
			{
				if ( ! is_null($id))
				{
					// Prepare the success message
					$success = Lang::get('admin/users/message.success.update');
				}
				else
				{
					// Prepare the success message
					$success = Lang::get('admin/users/message.success.create');
				}

				// Redirect to the user page
				return Redirect::route('update/user', $id)->with('success', $success);
			}

			// Prepare the error message
			$error = Lang::get('admin/users/message.error.update');
		}
		catch (UserNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('admin/users/message.not_found', compact('id'));

			// Redirect to the user management page
			return Redirect::route('users')->with('error', $error);
		}
		catch (LoginRequiredException $e)
		{
			// Prepare the error message
			$error = Lang::get('admin/users/message.login_required');

			// Redirect to the user page
			return Redirect::route('update/user', $id)->withInput()->with('error', $error);
		}
	}

}
