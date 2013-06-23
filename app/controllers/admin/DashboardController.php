<?php namespace Controllers\Admin;

use AdminController;
use Comment;
use Sentry;
use View;

class DashboardController extends AdminController {

	/**
	 * Show the administration dashboard page.
	 *
	 * @return View
	 */
	public function getIndex()
	{
		// Get the last 5 registered users
		$users = Sentry::getUserProvider()->createModel()->withTrashed()->take(5)->orderBy('created_at', 'DESC')->get();

		// Get the last 5 comments
		$comments = Comment::with(array(
			'article',
			'author' => function($query)
			{
				$query->withTrashed();
			},
		))->take(5)->orderBy('created_at', 'DESC')->get();

		// Show the page
		return View::make('backend/dashboard', compact('users', 'comments'));
	}

}
