<?php

class FilesController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$file_repository = storage_path()."/files/";
		return $file_repository;
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('files.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		$file_repository = Config::get('media.file-repository');
		if (Input::file('attachment')->isValid())
			{
			    $user = Auth::user();
			    $file = new Media;
			    $file->title = Input::file('attachment')->getClientOriginalName();
			    $file->extension = Input::file('attachment')->getClientOriginalExtension();
			    $file->size = Input::file('attachment')->getSize();
			    $file->mime = Input::file('attachment')->getMimeType();
			    $filename = str_random(12).".".$file->extension;
			    $file->author()->associate($user);
			    if (Input::file('attachment')->move($file_repository, $filename))
			    {
			    	$file->path = $filename;
			    	$file->save();
			    	return Response::json('success', 200);
			    } else {
			    	return Response::json('error', 400);
			    }
			}
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$file_repository = Config::get('media.file-repository');
		$file = Media::find($id);
		$headers = array('Content-Type: '.$file->mime);
		return Response::download($file_repository.$file->path, $file->title, $headers);
		
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}


}
