<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreThumbnail;
use App\Userthumbnail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserthumbnailController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	const ID_LENGTH = 12;
	private function getRandomFilestring()
	{
		$characters = array_merge(
			range(0, 9),
			range('a', 'z'),
			range('A', 'Z'),
			['-', '_']
		);

		$length = count($characters);
		$filestring = "";
		for ($i = 0; $i < self::ID_LENGTH; $i++) {
			$filestring .= $characters[random_int(0, $length - 1)];
		}

		return $filestring;
	}

	public function create(Request $request)
	{
		$userthumbnail = new Userthumbnail();
		$userthumbnail->filename = $userthumbnail->filestring . '.png';
		Log::debug($userthumbnail);
		Auth::user()->userthumbnail()->save($userthumbnail);
		return response($userthumbnail, 201);
	}

	public function update(StoreThumbnail $request)
	{
		$extension = $request->userthumbnail->extension();

		$userid = Auth::id();

		$userthumbnail = Userthumbnail::where('user_id', $userid)->first();
		$userthumbnail->filestring = $this->getRandomFilestring();
		$userthumbnail->filename = $userthumbnail->filestring . '.' . $extension;

		Storage::cloud()
			->putFileAs('', $request->userthumbnail, $userthumbnail->filename, 'public');

		DB::beginTransaction();
		try {
			Auth::user()->userthumbnail()->save($userthumbnail);
			DB::commit();
		} catch (\Exception $exception) {
			DB::rollBack();
			Storage::cloud()->delete($userthumbnail->filename);
			throw $exception;
		}

		return response($userthumbnail, 200);
	}
}
