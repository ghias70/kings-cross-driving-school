<?php

namespace App\Http\Controllers;

use Illuminate\Support\Collection;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('home');
    }

    public function lessons(): View
    {
        return view('pages.lessons');
    }

    public function about(): View
    {
        return view('pages.about');
    }

    public function areas(): View
    {
        return view('pages.areas');
    }

    public function gallery(): View
    {
        $photos = Collection::make(scandir(public_path('images/gallery')) ?: [])
            ->reject(fn (string $file) => in_array($file, ['.', '..'], true))
            ->filter(fn (string $file) => preg_match('/\.(jpe?g|png|webp)$/i', $file) === 1)
            ->sort()
            ->values()
            ->map(fn (string $file) => 'images/gallery/'.$file);

        return view('pages.gallery', ['photos' => $photos]);
    }
}
