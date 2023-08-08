<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\Request;

use App\Http\Requests\UploadRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;


class UploadController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UploadRequest $request)
    {
        Gate::authorize('upload-files');

        $file = $request->file('file');
        $name = $file->hashName();

        if (!Storage::exists('avatars')) {
            Storage::makeDirectory('avatars');
        }

        $upload = Storage::put("avatars/{$name}", $file);

        Media::query()->create(
            attributes: [
                'name' => "{$name}",
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getClientMimeType(),
                'path' => "avatars/{$name}",
                'disk' => config('app.uploads.disk'),
                'file_hash' => hash_file(
                    config('app.uploads.hash'),
                    storage_path(
                        path: "avatars/{$name}",
                    ),
                ),
                'collection' => $request->get('collection'),
                'size' => $file->getSize(),
            ],
        );

        return redirect()->back();
    }
}
