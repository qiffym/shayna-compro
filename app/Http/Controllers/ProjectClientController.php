<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClientRequest;
use App\Models\ProjectClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProjectClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = ProjectClient::query()->latest()->paginate(10);
        return view('admin.clients.index', [
            'clients' => $clients,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClientRequest $request)
    {
        DB::transaction(function () use ($request) {
            $validatedData = $request->validated();

            if ($request->hasFile('avatar') && $request->hasFile('logo')) {
                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $logoPath = $request->file('logo')->store('logos', 'public');

                $validatedData['avatar'] = $avatarPath;
                $validatedData['logo'] = $logoPath;
            }

            $newClient = ProjectClient::create($validatedData);
        });

        return to_route('admin.clients.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProjectClient $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProjectClient $client)
    {
        return view('admin.clients.edit', [
            'client' => $client
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreClientRequest $request, ProjectClient $client)
    {
        DB::transaction(function () use ($request, $client) {
            $validatedData = $request->validated();

            if ($request->hasFile('avatar') || $request->hasFile('logo')) {
                if ($request->hasFile('avatar')) {
                    Storage::delete("public/$client->avatar");
                }
                if ($request->hasFile('logo')) {
                    Storage::delete("public/$client->logo");
                }

                $avatarPath = $request->file('avatar')->store('avatars', 'public');
                $logoPath = $request->file('logo')->store('logos', 'public');

                $validatedData['avatar'] = $avatarPath;
                $validatedData['logo'] = $logoPath;
            }

            $client->update($validatedData);
        });

        return to_route('admin.clients.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectClient $client)
    {
        DB::transaction(function () use ($client) {
            $client->delete();
        });

        return redirect()->back();
    }
}
