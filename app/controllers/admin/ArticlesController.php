<?php namespace Controllers\Admin;

use AdminController;
use Input;
use Lang;
use Post;
use Redirect;
use Sentry;
use Str;
use Validator;
use View;

class ArticlesController extends AdminController {

	/**
	 * Show a list of all the blog posts.
	 *
	 * @return View
	 */
	public function getIndex()
	{
		// Grab all the blog posts
		$posts = Post::orderBy('created_at', 'DESC')->paginate();

		// Show the page
		return View::make('backend/blogs/index', compact('posts'));
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
	 * Delete the given blog post.
	 *
	 * @param  int  $id
	 * @return Redirect
	 */
	public function getDelete($id)
	{
		// Check if the blog post exists
		if (is_null($post = Post::find($id)))
		{
			// Redirect to the blogs management page
			return Redirect::to('admin/blogs')->with('error', Lang::get('admin/blogs/message.not_found'));
		}

		// Delete the blog post
		$post->delete();

		// Redirect to the blog posts management page
		return Redirect::to('admin/blogs')->with('success', Lang::get('admin/blogs/message.delete.success'));
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
		$post = null;

		// Do we have the blog post id?
		if ( ! is_null($id))
		{
			// Check if the blog post exists
			if (is_null($post = Post::find($id)))
			{
				// Redirect to the blogs management page
				return Redirect::to('admin/blogs')->with('error', Lang::get('admin/blogs/message.not_found'));
			}
		}

		// Show the page
		return View::make('backend/blogs/form', compact('post', 'pageSegment'));
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
			// Check if the blog post exists
			if (is_null($post = Post::find($id)))
			{
				// Redirect to the blogs management page
				return Redirect::to('admin/blogs')->with('error', Lang::get('admin/blogs/message.not_found'));
			}
		}
		else
		{
			// Create a new blog post
			$post = new Post;
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

		// Update the blog post data
		$post->title            = Input::get('title');
		$post->slug             = Str::slug(Input::get('title'));
		$post->content          = Input::get('content');
		$post->meta_title       = Input::get('meta-title');
		$post->meta_description = Input::get('meta-description');
		$post->meta_keywords    = Input::get('meta-keywords');

		// Was the blog post updated?
		if($post->save())
		{
			// Redirect to the new blog post page
			return Redirect::to("admin/blogs/$id/edit")->with('success', Lang::get('admin/blogs/message.update.success'));
		}

		// Redirect to the blogs post management page
		return Redirect::to("admin/blogs/$id/edit")->with('error', Lang::get('admin/blogs/message.update.error'));
	}

}
