<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ImageController extends Controller
{
    /**
     * Serve cover images
     */
    public function cover($filename)
    {
        // Sanitize filename untuk keamanan
        $filename = basename($filename);
        
        // Cek di public/storage/covers (lokasi utama)
        $path = public_path('storage/covers/'.$filename);
        
        if (file_exists($path) && is_file($path)) {
            $file = file_get_contents($path);
            $type = mime_content_type($path);
            
            return response($file, 200)
                ->header('Content-Type', $type)
                ->header('Cache-Control', 'public, max-age=31536000')
                ->header('Content-Length', filesize($path));
        }
        
        // Fallback: cek di storage/app/public/covers
        $fallbackPath = storage_path('app/public/covers/'.$filename);
        if (file_exists($fallbackPath) && is_file($fallbackPath)) {
            $file = file_get_contents($fallbackPath);
            $type = mime_content_type($fallbackPath);
            
            return response($file, 200)
                ->header('Content-Type', $type)
                ->header('Cache-Control', 'public, max-age=31536000')
                ->header('Content-Length', filesize($fallbackPath));
        }
        
        // Return 404 jika tidak ditemukan
        abort(404, 'Image not found');
    }
}

