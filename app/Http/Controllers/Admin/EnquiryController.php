<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function index(): View
    {
        return view('admin.enquiries.index', [
            'enquiries' => Enquiry::query()->latest()->paginate(15),
        ]);
    }

    public function show(Enquiry $enquiry): View
    {
        if ($enquiry->read_at === null) {
            $enquiry->update(['read_at' => now()]);
        }

        return view('admin.enquiries.show', [
            'enquiry' => $enquiry,
        ]);
    }

    public function destroy(Enquiry $enquiry): RedirectResponse
    {
        $enquiry->delete();

        return redirect()->route('admin.enquiries.index')->with('status', 'Enquiry deleted.');
    }
}
