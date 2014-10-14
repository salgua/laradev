<?php

class Media extends \Eloquent {
	protected $fillable = [];

	protected $table = 'files';

	public static function boot()
	{
		parent::boot();

	}

	public function author()
	{
		return $this->belongsTo('User', 'author_id');
	}

}