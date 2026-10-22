<?php

namespace App\Http\Controllers;

use App\Models\Enquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(Request $request): View
    {
        return view('pages.contact', [
            'package' => $request->query('package'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'phone' => ['required', 'string', 'max:40'],
            'email' => ['nullable', 'email', 'max:160'],
            'package' => ['nullable', 'string', 'max:40'],
            'postcode' => ['nullable', 'string', 'max:16'],
            'message' => ['nullable', 'string', 'max:2000'],
        ]);

        if (! empty($data['package']) && ! array_key_exists($data['package'], config('school.packages'))) {
            $data['package'] = null;
        }

        Enquiry::query()->create($data);

        return redirect()
            ->route('contact')
            ->with('status', 'Message received. Your instructor will reply by phone. There is no online payment on this website yet.');
    }
}
