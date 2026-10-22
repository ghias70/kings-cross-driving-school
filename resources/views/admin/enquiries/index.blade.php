@extends('admin.layout')

@section('title', 'Enquiries')
@section('heading', 'Enquiries')
@section('subheading', 'Messages from the contact form.')

@section('content')
    <div class="overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-black/5">
        @forelse ($enquiries as $enquiry)
            <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="flex flex-wrap items-center justify-between gap-3 border-b border-lane-line px-5 py-4 last:border-0 hover:bg-lane/50">
                <div>
                    <div class="flex items-center gap-2">
                        <p class="font-bold text-ink">{{ $enquiry->name }}</p>
                        @if ($enquiry->read_at === null)
                            <span class="rounded-full bg-signal-soft px-2 py-0.5 text-[11px] font-bold uppercase tracking-wide text-signal">New</span>
                        @endif
                    </div>
                    <p class="text-sm text-slate-500">{{ $enquiry->phone }}@if($enquiry->email) · {{ $enquiry->email }}@endif @if($enquiry->package)· {{ $enquiry->package }}@endif</p>
                </div>
                <p class="text-sm font-semibold text-slate-500">{{ $enquiry->created_at->format('j M · g:i a') }}</p>
            </a>
        @empty
            <p class="px-5 py-10 text-sm text-slate-600">No enquiries yet.</p>
        @endforelse
    </div>

    <div class="mt-5">{{ $enquiries->links() }}</div>
@endsection
