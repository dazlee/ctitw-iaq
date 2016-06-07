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
    private $filesBasePath = '/files';
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
            'file' => 'required|mimes:jpg,jpeg,bmp,png,pdf,doc,docx|max:5120',
        ]);


        $file = $request->file('file');
        if ($file) {
            $fileName = $file->getClientOriginalName();

            $totalFileCount = UserFile::where("user_id", "=", $clientId)->get()->count();
            // check if there a file with same name
            $fileCount = UserFile::where("file_name", "=", $fileName)->where("user_id", "=", $clientId)->get()->count();
            $validator = Validator::make(['file_count' => $fileCount, 'file_limit' => $totalFileCount], [
                'file_count' => "integer|max:0",
                'file_limit' => "integer|max:9",
            ]);
            if ($validator->fails())
            {
                return Redirect::back()->withErrors($validator);
            }

            $client = Client::where("user_id", "=", $clientId)->first();
            $destinationPath = base_path() . $this->uploadBasePath . '/' . $client->user->username;
            if(!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, $mode = 0777, true, true);
            }

            // $current_time = Carbon::now()->timestamp;
            $file->move($destinationPath, $fileName);

            UserFile::create([
                'user_id'      => $clientId,
                'file_name' => $fileName,
                'path'     => $destinationPath,
            ]);
        }

        return Redirect::back();
    }

    public function downloadFile (Request $request, $file_id) {
        $userFile = UserFile::find($file_id);
        $filePath = $userFile->path . '/' . $userFile->file_name;
        return response()->download($filePath);
    }

    public function deleteFile (Request $request, $file_id) {
        $user = Auth::user();

        $userFile = UserFile::find($file_id);
        if ($userFile && $userFile->user_id == $user->id) {
            $filePath = $userFile->path . '/' . $userFile->file_name;
            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $userFile->delete();
        }

        return Redirect::back();
    }


    public function statsFiles()
    {
        return view('stats-files', [
        ]);
    }

    public function getStatsFiles(Request $request, $deviceAccount, $year, $quarter)
    {
        $path = base_path() . $this->filesBasePath . '/' . $deviceAccount . '/' . $year . '/' . $quarter;
        if(File::exists($path)) {

            $filename = $path . "/" . $year . '-' . $quarter . ".zip";
            if (File::exists($filename)) {
                File::delete($filename);
            }

            $files = scandir($path);
            $zip = new \ZipArchive;

            //If there is an issue... close
            if ($zip->open($filename, \ZipArchive::CREATE)!==TRUE) {
                exit("cannot open <$filename>\n");
            }else{
                $zip->addFromString('readme.txt', $year . '-' . $quarter);
                foreach($files as $file){
                    if ($file === '.' || $file === '..') {
                        continue;
                    }
                    $zip->addFile($path.'/'.$file, $file);
                }
                $zip->close();
            }

            return response()->download($filename);
        }
        return Redirect::back();
    }
}
