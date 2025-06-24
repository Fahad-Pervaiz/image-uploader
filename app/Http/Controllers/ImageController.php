<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImageController extends Controller
{
    public function index() {
        return view('images.index');
    }

    public function show() {
         return auth()->user()->images()->latest()->pluck('path')->toArray();
    }

    public function store(Request $request) {
        // Validate the incoming file
        if (!$request->hasFile('image')) {
            return response()->json(['error' => 'There is no imgage file'], 400);
        }

        $request->validate([
            'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:5MB',
        ]);

        // Store the image
        $path = $request->file('image')->store('images', 'public');

        if (!$path) {
            return response()->json(['error' => 'Failed to upload image'], 500);
        }

        $uploadedFile = $request->file('image');

        // Create a new Image model instance and save it to the database
        $image = Image::create([
            'user_id' => Auth::id(),
            'name' => $uploadedFile->getClientOriginalName(),
            'extension' => $uploadedFile->getClientOriginalExtension(),
            'size' => $uploadedFile->getSize(),
            'path' => $path,
        ]);

        return $image->path;
    }

    public function destroy($filename)
    {
        $path = 'images/' . $filename;

        $image = Image::where('path', $path)->where('user_id', auth()->id())->first();

        if (!$image || !\Storage::disk('public')->exists($path)) {
            return response()->json(['error' => 'File not found.'], 404);
        }

        // Delete file from storage
        \Storage::disk('public')->delete($path);

        // Delete from DB
        Image::where('path', $path)->delete();

        return response()->json(['message' => 'Image deleted successfully.']);
    }


}
