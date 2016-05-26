<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\File;

use App\Http\Requests;
use Auth;
use Carbon\Carbon;
use App\Client;
use App\UserFile;

class FilesController extends Controller
{
    private $uploadBasePath = '/uploads';
    private $formDataKey = 'file';

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index (Request $request) {
        $user = Auth::user();

        return view('files', [
            "user_id"   => $user->id,
        ]);
    }

    public function uploadFile (Request $request, $clientId) {
        // For files, size corresponds to the file size in kilobytes.
        // limit to 25 mb
        $this->validate($request, [
            'file' => 'required|mimes:jpg,jpeg,bmp,png,pdf,doc,docx|max:256000',
        ]);

        $client = Client::where("user_id", "=", $clientId)->first();
        $destinationPath = base_path() . $this->uploadBasePath . '/' . $client->user->username;
        if(!File::exists($destinationPath)) {
            File::makeDirectory($destinationPath, $mode = 0777, true, true);
        }

        $file = $request->file('file');
        if ($file) {
            // $current_time = Carbon::now()->timestamp;
            $fileName = $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);

            UserFile::create([
                'user_id'      => $clientId,
                'file_name' => $fileName,
                'path'     => $destinationPath,
            ]);
        }

        return Redirect::back();
    }
}
