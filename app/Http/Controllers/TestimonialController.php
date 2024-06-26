<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTestimonialRequest;
use App\Models\ProjectClient;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $testimonials = Testimonial::query()->latest()->paginate(10);
        return view('admin.testimonials.index', [
            'testimonials' => $testimonials,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = ProjectClient::query()->latest()->paginate(10);
        return view('admin.testimonials.create', [
            'clients' => $clients
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTestimonialRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validatedData = $request->validated();

            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $validatedData['thumbnail'] = $thumbnailPath;
            }

            $newTestimonial = Testimonial::create($validatedData);
        });

        return to_route('admin.testimonials.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Testimonial $testimonial)
    {
        $clients = ProjectClient::query()->whereNot('id', $testimonial->project_client_id)->latest()->paginate(10);
        return view('admin.testimonials.edit', [
            'testimonial' => $testimonial,
            'clients' => $clients
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreTestimonialRequest $request, Testimonial $testimonial)
    {
        DB::transaction(function () use ($request, $testimonial) {
            $validatedData = $request->validated();

            if ($request->hasFile('thumbnail')) {
                Storage::delete("public/$testimonial->thumbnail");
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $validatedData['thumbnail'] = $thumbnailPath;
            }

            $testimonial->update($validatedData);
        });

        return to_route('admin.testimonials.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        DB::transaction(function () use ($testimonial) {
            $testimonial->delete();
        });

        return redirect()->back();
    }
}
