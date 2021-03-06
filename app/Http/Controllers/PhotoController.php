<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Photo;

class PhotoController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function getAdd() {
		return view('mylove/addphoto');
	}

	public function postAdd(Request $request) {
		$url = $request->input('url');

		if(strpos($url,'drive.google.com/open?id=')) {
			$arr = explode("?id=",$url);
			$url = 'https://docs.google.com/uc?id='.$arr[1];
		}
		else if (strpos($url,'drive.google.com/file/d')) {
			$arr = explode("/", $url);
			if(strpos($url, '//')) {
				$url = 'https://docs.google.com/uc?id='.$arr[5];
			} else {
				$url = 'https://docs.google.com/uc?id='.$arr[3];
			}
		}

		$caption = $request->input('caption');

		$photo = new Photo;
		$photo->url = $url;
		if($caption!="") {
			$photo->caption = $caption;
			$photo->style = $request->input('style');
		}
		$photo->save();

		if($request->ajax()) {
			// return view('mylove/singleSlide')->with('photo', $photo);
			return "ok";
		}
		return redirect('mylove');
	}

	function getManage () {
		return redirect('mylove');
	}

	function getDelete(Request $request) {
		$id = $request->input('id');
		$photo = Photo::find($id);
		$photo->delete();
		if($request->ajax()) {
			return 'ok';
		}
		return redirect('mylove');
	}
}
