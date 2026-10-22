@extends('admin.layout')

@section('title', $enquiry->name)
@section('heading', $enquiry->name)
@section('subheading', 'Received '.$enquiry->created_at->format('l j F Y · g:i a'))

@section('content')
    <div class="mb-5 flex flex-wrap items-center justify-between gap-3">
        <a class="text-sm font-semibold text-signal hover:underline" href="{{ route('admin.enquiries.index') }}">← Back to enquiries</a>
        <form method="post" action="{{ route('admin.enquiries.destroy', $enquiry) }}" onsubmit="return confirm('Delete this enquiry?')">
            @csrf
            @method('DELETE')
            <button class="rounded-xl bg-rose-600 px-4 py-2 text-sm font-bold text-white" type="submit">Delete</button>
        </form>
    </div>

    <section class="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5">
        <dl class="grid gap-4 sm:grid-cols-2">
            <div>
                <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Phone</dt>
                <dd class="mt-1"><a class="font-semibold underline" href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a></dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Email</dt>
                <dd class="mt-1 break-all">
                    @if ($enquiry->email)
                        <a class="font-semibold underline" href="mailto:{{ $enquiry->email }}">{{ $enquiry->email }}</a>
                    @else
                        —
                    @endif
                </dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Package interest</dt>
                <dd class="mt-1 font-semibold">{{ $enquiry->package ?: '—' }}</dd>
            </div>
            <div>
                <dt class="text-xs font-bold uppercase tracking-wide text-slate-500">Postcode</dt>
                <dd class="mt-1 font-semibold">{{ $enquiry->postcode ?: '—' }}</dd>
            </div>
        </dl>
        <div class="mt-6 rounded-2xl bg-lane px-4 py-4 text-sm leading-relaxed text-ink">
            {{ $enquiry->message ?: 'No message left.' }}
        </div>
    </section>
@endsection
