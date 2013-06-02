<?php namespace Controllers\Admin;

use AdminController;
use Cartalyst\Sentry\Groups\GroupExistsException;
use Cartalyst\Sentry\Groups\GroupNotFoundException;
use Cartalyst\Sentry\Groups\NameRequiredException;
use Config;
use Input;
use Lang;
use Redirect;
use Sentry;
use Validator;
use View;

class GroupsController extends AdminController {

	/**
	 * Holds the form validation rules.
	 *
	 * @var array
	 */
	protected $validationRules = array(
		'name' => 'required',
	);

	/**
	 * Show a list of all the groups.
	 *
	 * @return View
	 */
	public function getIndex()
	{
		// Grab all the groups
		$groups = Sentry::getGroupProvider()->createModel()->paginate();

		// Show the page
		return View::make('backend/groups/index', compact('groups'));
	}

	/**
	 * Group create.
	 *
	 * @return View
	 */
	public function getCreate()
	{
		return $this->showForm(null, 'create');
	}

	/**
	 * Group create form processing.
	 *
	 * @return Redirect
	 */
	public function postCreate()
	{
		return $this->processForm();
	}

	/**
	 * Group update.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function getEdit($id = null)
	{
		return $this->showForm($id, 'update');
	}

	/**
	 * Group update form processing page.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function postEdit($id = null)
	{
		return $this->processForm($id);
	}

	/**
	 * Delete the given group.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function getDelete($id = null)
	{
		try
		{
			// Get group information
			$group = Sentry::getGroupProvider()->findById($id);

			// Delete the group
			$group->delete();

			// Redirect to the group management page
			return Redirect::route('groups')->with('success', Lang::get('admin/groups/message.delete.success'));
		}
		catch (GroupNotFoundException $e)
		{
			// Redirect to the group management page
			return Redirect::route('groups')->with('error', Lang::get('admin/groups/message.not_found', compact('id')));
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
			// Group fallback data
			$group            = null;
			$groupPermissions = array();

			// Do we have the group id?
			if ( ! is_null($id))
			{
				// Get the group information
				$group = Sentry::getGroupProvider()->findById($id);

				// Get this group permissions
				$groupPermissions = $group->getPermissions();
			}

			// Get all the available permissions
			$permissions = Config::get('permissions');
			$this->encodeAllPermissions($permissions, true);

			// Prepare the group permissions
			$this->encodePermissions($groupPermissions);
			$groupPermissions = array_merge($groupPermissions, Input::old('permissions', array()));

			// Show the page
			return View::make('backend/groups/form', compact('group', 'permissions', 'groupPermissions', 'pageSegment'));
		}
		catch (GroupNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('admin/groups/message.not_found', compact('id'));

			// Redirect to the groups management page
			return Redirect::route('groups')->with('error', $error);
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
			// permissions here before we update the group.
			$permissions = Input::get('permissions', array());
			$this->decodePermissions($permissions);
			app('request')->request->set('permissions', $permissions);

			if ( ! is_null($id))
			{
				// Get the group information
				$group = Sentry::getGroupProvider()->findById($id);
			}
			else
			{
				// Create a new group
				$group = Sentry::getGroupProvider()->createModel();
			}

			// Create a new validator instance from our validation rules
			$validator = Validator::make(Input::all(), $this->validationRules);

			// If validation fails, we'll exit the operation now.
			if ($validator->fails())
			{
				// Ooops.. something went wrong
				return Redirect::back()->withInput()->withErrors($validator);
			}

			// Update the group data
			$group->name        = Input::get('name');
			$group->permissions = Input::get('permissions');

			// Was the group updated?
			if ($group->save())
			{
				// Redirect to the group page
				return Redirect::route('update/group', $id)->with('success', Lang::get('admin/groups/message.success.update'));
			}

			// Prepare the error message
			$error = Lang::get('admin/groups/message.error.update');
		}
		catch (GroupNotFoundException $e)
		{
			// Prepare the error message
			$error = Lang::get('admin/groups/message.not_found', compact('id'));

			// Redirect to the groups management page
			return Redirect::route('groups')->with('error', $error);
		}
		catch (GroupExistsException $e)
		{
			$error = Lang::get('admin/groups/message.group_exists');
		}
		catch (NameRequiredException $e)
		{
			$error = Lang::get('admin/groups/message.name_required');
		}

		// Redirect to the appropriate page
		if ( ! is_null($id))
		{
			return Redirect::route('update/group', $id)->withInput()->with('error', $error);
		}

		return Redirect::route('create/group')->withInput()->with('error', $error);
	}

}
