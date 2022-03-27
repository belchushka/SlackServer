<?php

namespace App\Http\Controllers;

use App\Http\Requests\albumCreateRequest;
use App\Http\Resources\ErrorResource;
use App\Models\Album;
use App\Models\Song;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Exceptions\HttpResponseException;

class AlbumController extends Controller
{
    public function createAlbum(albumCreateRequest $request)
    {
        $songs = collect($request->get("songs"));
        $songsFiles = $request->file("songs");
        $icon = $request->file("icon");
        $extension = $icon->getClientOriginalExtension();
        $iconPath = Carbon::now()->getTimestamp().".".$extension;
        $icon->move(public_path("albums"),$iconPath);
        $album = Album::create([
           "name"=>$request->get("name"),
           "icon"=>request()->getHttpHost()."/albums/".$iconPath
        ]);
        $songs->each(function ($song, $id)use($songsFiles, $album){
            $file = $songsFiles[$id]["file"];
            $extension = $file->getClientOriginalExtension();
            $filePath = Carbon::now()->getTimestamp().".".$extension;
            $file->move(public_path("songs"),$filePath);
            $album->songs()->save(new Song([
                "name"=>$song["name"],
                "fileLink"=>request()->getHttpHost()."/songs/".$filePath
            ]));
        });

        return response()->json(["success"=>true],200);

    }

    public function getLatestAlbums()
    {
        $albums = Album::orderBy('created_at', 'DESC')->get();
        return response()->json([
            "data"=>$albums
        ]);
    }

    public function getAlbum($id)
    {
        try{
            $album = Album::findOrFail($id);
            return response()->json(["data"=>$album],200);
        }catch (ModelNotFoundException $e){
            throw new HttpResponseException(response()->json(new ErrorResource("Album not found"),404));
        }
    }
}
