<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileDownloadRequest;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function download(FileDownloadRequest $request, File $file)
    {
        return Storage::disk($file->disk)->download(
            $file->path,
            $file->original,
            [
                'Content-type' => $file->mime,
            ]
        );
    }

    public function setFile(Request $request)
    {
        if ($request->isMethod('post') && $request->file('file')) {

            $file = $request->file('file');
            $upload_folder = 'public/upload_files';
            $filename = $file->getClientOriginalName();

            Storage::putFileAs($upload_folder, $file, $filename);

            //return redirect('admin/sessions');

        }
    }
}
