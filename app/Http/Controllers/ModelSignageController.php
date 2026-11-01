<?php

namespace App\Http\Controllers;

use App\Models\ModelSignage;
use Illuminate\Http\Request;

class ModelSignageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ModelSignage::query();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('nama_model', 'like', "%$search%")
                  ->orWhere('merk', 'like', "%$search%")
                  ->orWhere('resolusi', 'like', "%$search%");
        }

        $models = $query->orderBy('nama_model')->paginate(10)->withQueryString();

        return view('model-signage.index', compact('models'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('model-signage.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Hanya memvalidasi kolom yang benar-benar ada di database Anda
        $validated = $request->validate([
            'nama_model' => 'required|string|max:255',
            'merk'       => 'nullable|string|max:255',
            'resolusi'   => 'nullable|string|max:50',
            'orientasi'  => 'nullable|in:landscape,portrait',
            'keterangan' => 'nullable|string',
        ], [
            'nama_model.required' => 'Nama model TV / perangkat wajib diisi.',
        ]);

        // 🎯 FIX: Kita tidak menambahkan properti 'aktif' ke dalam array database
        ModelSignage::create($validated);

        return redirect()->route('model-signage.index')
                         ->with('success', 'Model signage berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ModelSignage $modelSignage)
    {
        return view('model-signage.show', compact('modelSignage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ModelSignage $modelSignage)
    {
        return view('model-signage.edit', compact('modelSignage'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ModelSignage $modelSignage)
    {
        $validated = $request->validate([
            'nama_model' => 'required|string|max:255',
            'merk'       => 'nullable|string|max:255',
            'resolusi'   => 'nullable|string|max:50',
            'orientasi'  => 'nullable|in:landscape,portrait',
            'keterangan' => 'nullable|string',
        ], [
            'nama_model.required' => 'Nama model TV / perangkat wajib diisi.',
        ]);

        $modelSignage->update($validated);

        return redirect()->route('model-signage.index')
                         ->with('success', 'Model signage berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ModelSignage $modelSignage)
    {
        $modelSignage->delete();

        return redirect()->route('model-signage.index')
                         ->with('success', 'Model signage berhasil dihapus.');
    }
}