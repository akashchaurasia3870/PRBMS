<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class FileUploadMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Allowed extensions
            $allowedExtensions = [
                'jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp',
                'pdf',
                'xls', 'xlsx',
                'doc', 'docx',
                'ppt', 'pptx',
                'txt',
                'mp4', 'avi', 'mov', 'mkv'
            ];

            $extension = strtolower($file->getClientOriginalExtension());

            if (!in_array($extension, $allowedExtensions)) {
                return response()->json(['error' => 'Unsupported file type.'], 400);
            }

            // Store file in public/uploads
            $path = $file->store('uploads', 'public');

            // Inject file path into the request
            $request->merge([
                'file_path' => Storage::url($path)
            ]);
        }

        // Continue to next middleware / controller
        return $next($request);
    }
}
