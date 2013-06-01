<?php namespace Controllers\Admin;

use AdminController;
use Article;
use Input;
use Lang;
use Redirect;
use Sentry;
use Str;
use Validator;
use View;

class ArticlesController extends AdminController {

	/**
	 * Show a list of all the blog articles.
	 *
	 * @return View
	 */
	public function getIndex()
	{
		// Grab all the blog articles
		$articles = Article::orderBy('created_at', 'DESC')->paginate();

		// Show the page
		return View::make('backend/blogs/index', compact('articles'));
	}

	/**
	 * Blog article create.
	 *
	 * @return View
	 */
	public function getCreate()
	{
		return $this->showForm(null, 'create');
	}

	/**
	 * Blog article create form processing.
	 *
	 * @return Redirect
	 */
	public function postCreate()
	{
		return $this->processForm();
	}

	/**
	 * Blog article update.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function getEdit($id = null)
	{
		return $this->showForm($id, 'update');
	}

	/**
	 * Blog article update form processing page.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function postEdit($id = null)
	{
		return $this->processForm($id);
	}

	/**
	 * Blog article copy.
	 *
	 * @param  int  $id
	 * @return View
	 */
	public function getCopy($id = null)
	{
		return $this->showForm($id, 'copy');
	}

	/**
	 * Blog article copy form processing.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function postCopy($id = null)
	{
		return $this->processForm($id);
	}

	/**
	 * Delete the given blog article.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function getDelete($id)
	{
		// Check if the blog article exists
		if (is_null($article = Article::find($id)))
		{
			// Redirect to the blogs management page
			return Redirect::to('admin/articles')->with('error', Lang::get('admin/blogs/message.not_found'));
		}

		// Delete the blog article
		$article->delete();

		// Redirect to the blog posts management page
		return Redirect::to('admin/articles')->with('success', Lang::get('admin/blogs/message.delete.success'));
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
		// Fallback data
		$article = null;

		// Do we have the blog post id?
		if ( ! is_null($id))
		{
			// Check if the blog post exists
			if (is_null($article = Article::find($id)))
			{
				// Redirect to the blogs management page
				return Redirect::to('admin/articles')->with('error', Lang::get('admin/blogs/message.not_found'));
			}
		}

		// Show the page
		return View::make('backend/blogs/form', compact('article', 'pageSegment'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	protected function processForm($id = null)
	{
		if ( ! is_null($id))
		{
			// Check if the blog article exists
			if (is_null($article = Article::find($id)))
			{
				// Redirect to the blogs management page
				return Redirect::to('admin/blogs')->with('error', Lang::get('admin/blogs/message.not_found'));
			}
		}
		else
		{
			// Create a new blog article
			$article = new Article;
		}

		// Declare the rules for the form validation
		$rules = array(
			'title'   => 'required|min:3',
			'content' => 'required|min:3',
		);

		// Create a new validator instance from our validation rules
		$validator = Validator::make(Input::all(), $rules);

		// If validation fails, we'll exit the operation now.
		if ($validator->fails())
		{
			// Ooops.. something went wrong
			return Redirect::back()->withInput()->withErrors($validator);
		}

		// Update the blog article data
		$article->title            = Input::get('title');
		$article->slug             = Str::slug(Input::get('title'));
		$article->content          = Input::get('content');
		$article->meta_title       = Input::get('meta-title');
		$article->meta_description = Input::get('meta-description');
		$article->meta_keywords    = Input::get('meta-keywords');

		// Was the blog post updated?
		if($article->save())
		{
			// Redirect to the new blog post page
			return Redirect::to("admin/articles/$id/edit")->with('success', Lang::get('admin/blogs/message.update.success'));
		}

		// Redirect to the blogs post management page
		return Redirect::to("admin/articles/$id/edit")->with('error', Lang::get('admin/blogs/message.update.error'));
	}

}
