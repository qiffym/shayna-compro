<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePrincipleRequest;
use App\Models\OurPrinciple;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OurPrincipleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $principles = OurPrinciple::query()->latest()->paginate(10);
        return view('admin.principles.index', [
            'principles' => $principles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.principles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePrincipleRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validatedData = $request->validated();
            if ($request->hasFile('thumbnail') && $request->hasFile('icon')) {
                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $iconPath = $request->file('icon')->store('icons', 'public');

                $validatedData['thumbnail'] = $thumbnailPath;
                $validatedData['icon'] = $iconPath;
            }

            $newPrinciple = OurPrinciple::create($validatedData);
        });

        return to_route('admin.principles.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(OurPrinciple $principle)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OurPrinciple $principle)
    {
        return view('admin.principles.edit', [
            'principle' => $principle
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePrincipleRequest $request, OurPrinciple $principle)
    {
        DB::transaction(function () use ($request, $principle) {
            $validatedData = $request->validated();

            if ($request->hasFile('thumbnail') || $request->hasFile('icon')) {
                if ($request->hasFile('thumbnail')) {
                    Storage::delete("public/$principle->thumbnail");
                }
                if ($request->hasFile('icon')) {
                    Storage::delete("public/$principle->icon");
                }

                $thumbnailPath = $request->file('thumbnail')->store('thumbnails', 'public');
                $iconPath = $request->file('icon')->store('icons', 'public');

                $validatedData['thumbnail'] = $thumbnailPath;
                $validatedData['icon'] = $iconPath;
            }

            $principle->update($validatedData);
        });

        return to_route('admin.principles.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OurPrinciple $principle)
    {
        DB::transaction(function () use ($principle) {
            $principle->delete();
        });

        return redirect()->back();
    }
}
