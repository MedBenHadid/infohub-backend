<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Http\Resources\InfoResource;
use App\Models\File;
use App\Models\Info;
use Illuminate\Http\Request;

class FileController extends Controller
{

    public function uploadFile(Request $request)
{
    
    if ($request->hasFile('file')) {
        $file = $request->file('file');
        $filename = $file->getClientOriginalName();
        $path = $file->storeAs('public/files', $filename);

        $info = new Info();
        $info->name = $request->input('name');
        $info->file_path = 'storage/files/' . $filename;
        $info->save();

        return response()->json(new InfoResource($info), 201);
    }

    return response()->json(['error' => 'File not found'], 400);
}
    public function getAllFiles(Request $request)
    {
        $files = File::all();
        return FileResource::collection($files);
    }   
    public function getFileContent(Request $request)
    {
        $file_path = $request->input('file_path');

        $path = storage_path('app/public/file/' . $file_path);

        if (file_exists($path)) {
            $content = file_get_contents($path);
            return response()->json([
                'filename' => $file_path,
                'content' => $content,
            ]);
        } else {
            return response()->json(['message' => 'File not found'], 404);
        }
    }
}
