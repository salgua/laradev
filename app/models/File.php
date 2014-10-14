<?php

class File extends \Eloquent {
	protected $fillable = [];

	public static function boot()
	{
		parent::boot();

	}

	public function author()
	{
		return $this->belongsTo('User', 'author_id');
	}

}