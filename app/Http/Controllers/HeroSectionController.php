<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreHeroSectionRequest;
use App\Models\HeroSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HeroSectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $hero_sections = HeroSection::query()->latest()->paginate(10);
        return view('admin.hero_sections.index', [
            'hero_sections' => $hero_sections,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.hero_sections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHeroSectionRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validatedData = $request->validated();

            if ($request->hasFile('banner')) {
                $bannerPath = $request->file('banner')->store('banners', 'public');
                $validatedData['banner'] = $bannerPath;
            }

            $newHeroSection = HeroSection::create($validatedData);
        });

        return to_route('admin.hero_sections.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(HeroSection $hero_section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HeroSection $hero_section)
    {
        return view('admin.hero_sections.edit', compact('hero_section'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreHeroSectionRequest $request, HeroSection $hero_section)
    {
        DB::transaction(function () use ($request, $hero_section) {
            $validatedData = $request->validated();

            if ($request->hasFile('banner')) {
                Storage::delete("public/$hero_section->banner");

                $bannerPath = $request->file('banner')->store('banners', 'public');
                $validatedData['banner'] = $bannerPath;
            }

            $hero_section->update($validatedData);
        });

        return to_route('admin.hero_sections.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HeroSection $hero_section)
    {
        DB::transaction(function () use ($hero_section) {
            $hero_section->delete();
        });

        return redirect()->back();
    }
}
