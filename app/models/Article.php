<?php

class Article extends Eloquent {

	/**
	 * Deletes a blog article and all the associated comments.
	 *
	 * @return bool
	 */
	public function delete()
	{
		// Delete the comments
		$this->comments()->delete();

		// Delete the blog article
		return parent::delete();
	}

	/**
	 * Returns a formatted article content entry, this ensures that
	 * line breaks are returned.
	 *
	 * @return string
	 */
	public function content()
	{
		return nl2br($this->content);
	}

	/**
	 * Return the article's author.
	 *
	 * @return User
	 */
	public function author()
	{
		return $this->belongsTo('User', 'user_id');
	}

	/**
	 * Return how many comments this article has.
	 *
	 * @return array
	 */
	public function comments()
	{
		return $this->hasMany('Comment');
	}

	/**
	 * Return the URL to the article.
	 *
	 * @return string
	 */
	public function url()
	{
		return URL::route('view-article', $this->slug);
	}

	/**
	 * Return the article thumbnail image url.
	 *
	 * @return string
	 */
	public function thumbnail()
	{
		# you should save the image url on the database
		# and return that url here.

		return 'http://lorempixel.com/130/90/business/';
	}

}
