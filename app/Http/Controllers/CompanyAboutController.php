<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAboutRequest;
use App\Models\CompanyAbout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyAboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $abouts = CompanyAbout::query()->latest()->paginate(10);
        return view('admin.abouts.index', [
            'abouts' => $abouts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.abouts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAboutRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validatedData = $request->validated();
            $keypoints = $validatedData['keypoints'];

            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $validatedData['thumbnail'] = $thumbnailPath;
            }

            $newAbout = CompanyAbout::create($validatedData);

            collect($keypoints)->each(fn ($keypoint) => $newAbout->keypoints()->create(['keypoint' => $keypoint]));
        });

        return to_route('admin.abouts.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyAbout $about)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyAbout $about)
    {
        return view('admin.abouts.edit', [
            'about' => $about
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreAboutRequest $request, CompanyAbout $about)
    {
        DB::transaction(function () use ($request, $about) {
            $validatedData = $request->validated();
            $keypoints = $validatedData['keypoints'];

            if ($request->hasFile('thumbnail')) {
                Storage::delete("public/$about->thumbnail");

                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $validatedData['thumbnail'] = $thumbnailPath;
            }

            $about->update($validatedData);

            $about->keypoints()->delete();
            collect($keypoints)->each(fn ($keypoint) => $about->keypoints()->create(['keypoint' => $keypoint]));
        });

        return to_route('admin.abouts.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyAbout $about)
    {
        DB::transaction(function () use ($about) {
            $about->delete();
        });

        return redirect()->back();
    }
}
