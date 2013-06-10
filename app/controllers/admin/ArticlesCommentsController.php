<?php namespace Controllers\Admin;

use AdminController;
use Article;
use Comment;
use Input;
use Lang;
use Redirect;
use Sentry;
use Str;
use Validator;
use View;

class ArticlesCommentsController extends AdminController {

	public function getIndex()
	{
		// Get all the comments
		$comments = Comment::with('article')->orderBy('created_at', 'DESC')->paginate();

		// Show the page
		return View::make('backend/articles/comments/index', compact('comments'));
	}

	public function getEdit($id = null)
	{

	}

}
