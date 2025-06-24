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

    // public function store(Request $request) {
    //     // Validate the incoming file
    //     if (!$request->hasFile('image')) {
    //         return response()->json(['error' => 'There is no image file'], 400);
    //     }

    //     $request->validate([
    //         'image' => 'required|file|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
    //     ]);

    //     // Store the image
    //     $path = $request->file('image')->store('images', 'public');

    //     if (!$path) {
    //         return response()->json(['error' => 'Failed to upload image'], 500);
    //     }

    //     $uploadedFile = $request->file('image');

    //     // Create a new Image model instance and save it to the database
    //     $image = Image::create([
    //         'user_id' => Auth::id(),
    //         'name' => $uploadedFile->getClientOriginalName(),
    //         'extension' => $uploadedFile->getClientOriginalExtension(),
    //         'size' => $uploadedFile->getSize(),
    //         'path' => $path,
    //     ]);

    //     return $image->path;
    // }
    public function store(Request $request) {
    try {
        // Validate file existence
        if (!$request->hasFile('image')) {
            return response()->json([
                'error' => 'No image file was uploaded',
                'received_data' => array_keys($request->all())
            ], 400);
        }

        // Additional validation with sanitization
        $validated = $request->validate([
            'image' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,png,jpg,gif,svg',
                'max:2048',
                function ($attribute, $value, $fail) {
                    // Sanitize filename check
                    $originalName = $value->getClientOriginalName();
                    if (preg_match('/[^\w\.\- ]/', $originalName)) {
                        $fail('Filename contains invalid characters. Only letters, numbers, spaces, hyphens, underscores and dots are allowed.');
                    }

                    // Verify image is readable
                    if (!$value->isValid()) {
                        $fail('The image file appears to be corrupted.');
                    }

                    // Check MIME type consistency
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/svg+xml'];
                    $guessedMime = $value->getMimeType();
                    if (!in_array($guessedMime, $allowedMimes)) {
                        $fail('Invalid file type. Detected: ' . $guessedMime);
                    }
                },
            ],
        ]);

        // Get the uploaded file
        $uploadedFile = $request->file('image');

        // Sanitize filename
        $originalName = $uploadedFile->getClientOriginalName();
        $extension = $uploadedFile->getClientOriginalExtension();
        $sanitizedName = $this->sanitizeFilename(pathinfo($originalName, PATHINFO_FILENAME));
        $safeFilename = $sanitizedName . '.' . $extension;

        // Store with sanitized filename
        $path = $uploadedFile->storeAs('images', $safeFilename, 'public');

        if (!$path) {
            throw new \Exception('Failed to store the uploaded file');
        }

        // Create image record
        $image = Image::create([
            'user_id' => Auth::id(),
            'name' => $safeFilename, // Store sanitized name
            // 'original_name' => $originalName, // Keep original for reference
            'extension' => $extension,
            'size' => $uploadedFile->getSize(),
            'path' => $path,
        ]);

        return response()->json([
            'success' => true,
            'path' => $path,
            'filename' => $safeFilename
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'error' => 'Validation failed',
            'messages' => $e->validator->errors()->messages(),
            'received_file' => $request->hasFile('image') ? [
                'name' => $request->file('image')->getClientOriginalName(),
                'size' => $request->file('image')->getSize(),
                'type' => $request->file('image')->getMimeType()
            ] : null
        ], 422);

    } catch (\Exception $e) {
        \Log::error('File upload failed: ' . $e->getMessage(), [
            'exception' => $e,
            'file_info' => $request->hasFile('image') ? [
                'name' => $request->file('image')->getClientOriginalName(),
                'size' => $request->file('image')->getSize()
            ] : null
        ]);

        return response()->json([
            'error' => 'File upload failed',
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Sanitize filename helper function
 */
protected function sanitizeFilename(string $filename): string
{
    // Remove special characters
    $clean = preg_replace("/[^a-zA-Z0-9\-_\.\s]/", "", $filename);

    // Replace spaces with underscores
    $clean = preg_replace("/\s+/", "_", $clean);

    // Limit length
    $clean = substr($clean, 0, 100);

    // Ensure it doesn't start/end with dot or dash
    $clean = trim($clean, '.-_');

    // Fallback if everything was removed
    if (empty($clean)) {
        $clean = 'upload_' . time();
    }

    return $clean;
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
