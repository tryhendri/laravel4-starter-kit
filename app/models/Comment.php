<?php

class Comment extends Eloquent {

	/**
	 * Get the comment's content.
	 *
	 * @return string
	 */
	public function content()
	{
		return nl2br(e($this->content));
	}

	/**
	 * Get the comment's author.
	 *
	 * @return User
	 */
	public function author()
	{
		return $this->belongsTo('User', 'user_id');
	}

	/**
	 * Get the comment's article's.
	 *
	 * @return Article
	 */
	public function article()
	{
		return $this->belongsTo('Article', 'article_id');
	}

}
