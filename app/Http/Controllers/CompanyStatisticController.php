<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStatisticRequest;
use App\Models\CompanyStatistic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CompanyStatisticController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statistics = CompanyStatistic::query()->latest()->paginate(10);
        return view('admin.statistics.index', [
            'statistics' => $statistics,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.statistics.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStatisticRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validatedData = $request->validated();

            if ($request->hasFile('icon')) {
                $iconPath = $request->file('icon')->store('icons', 'public');
                $validatedData['icon'] = $iconPath;
            }

            $newStatistic = CompanyStatistic::create($validatedData);
        });

        return to_route('admin.statistics.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(CompanyStatistic $statistic)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CompanyStatistic $statistic)
    {
        return view('admin.statistics.edit', [
            'statistic' => $statistic,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreStatisticRequest $request, CompanyStatistic $statistic)
    {
        DB::transaction(function () use ($request, $statistic) {
            $validatedData = $request->validated();

            if ($request->hasFile('icon')) {
                Storage::delete("public/$statistic->icon");

                $iconPath = $request->file('icon')->store('icons', 'public');
                $validatedData['icon'] = $iconPath;
            }

            $statistic->update($validatedData);
        });

        return to_route('admin.statistics.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CompanyStatistic $statistic)
    {
        DB::transaction(function () use ($statistic) {
            $statistic->delete();
        });

        return redirect()->back();
    }
}
