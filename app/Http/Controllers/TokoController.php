<?php

namespace App\Http\Controllers;

use App\Models\Toko;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function index(Request $request)
    {
        $query = Toko::query();

        if ($request->filled('area')) {
            $query->where('area', $request->area);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama_toko', 'like', "%$search%")
                  ->orWhere('kode_toko', 'like', "%$search%")
                  ->orWhere('channel', 'like', "%$search%")
                  ->orWhere('area', 'like', "%$search%");
            });
        }

        $toko = $query->orderBy('nama_toko')->paginate(10)->withQueryString();
        return view('toko.index', compact('toko'));
    }

    public function create()
    {
        return view('toko.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko'    => 'required|string|max:255',
            'kode_toko'    => ['required', 'string', 'max:50', \App\Support\Tenant::unique('toko', 'kode_toko')],
            'channel'      => 'required|string|max:100',
            'alamat'       => 'required|string',
            'kota'         => 'required|string|max:100',
            'provinsi'     => 'required|string|max:100',
            'area'         => 'required|string|max:100',
            'sim_card'     => 'nullable|string|max:100',
            'telepon'      => 'nullable|string|max:50',
            'jam_buka'     => 'required|date_format:H:i',
            'jam_tutup'    => 'required|date_format:H:i',
            'area_teknisi' => 'required|string|max:100',
            'keterangan'   => 'nullable|string',
        ]);

        Toko::create($validated);
        return redirect()->route('toko.index')->with('success', 'Toko berhasil ditambahkan.');
    }

    public function edit(Toko $toko)
    {
        return view('toko.edit', compact('toko'));
    }

    public function update(Request $request, Toko $toko)
    {
        $validated = $request->validate([
            'nama_toko'    => 'required|string|max:255',
            'kode_toko'    => ['required', 'string', 'max:50', \App\Support\Tenant::unique('toko', 'kode_toko', $toko->id)],
            'channel'      => 'required|string|max:100',
            'alamat'       => 'required|string',
            'kota'         => 'required|string|max:100',
            'provinsi'     => 'required|string|max:100',
            'area'         => 'required|string|max:100',
            'sim_card'     => 'nullable|string|max:100',
            'telepon'      => 'nullable|string|max:50',
            'jam_buka'     => 'required|date_format:H:i',
            'jam_tutup'    => 'required|date_format:H:i',
            'area_teknisi' => 'required|string|max:100',
            'keterangan'   => 'nullable|string',
        ]);

        $toko->update($validated);
        return redirect()->route('toko.index')->with('success', 'Toko berhasil diperbarui.');
    }

    public function destroy(Toko $toko)
    {
        $toko->delete();
        return redirect()->route('toko.index')->with('success', 'Toko berhasil dihapus.');
    }
}