<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lokasi;

class LokasiController extends Controller
{
    public function index()
    {
        $lokasis = Lokasi::all();
        return response()->json($lokasis, 200);
    }

    public function show($id)
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return response()->json(['error' => 'Lokasi not found'], 404);
        }

        return response()->json($lokasi, 200);
    }

    public function store(Request $request)
    {
        if (empty($request->all())) {
            return response()->json(['error' => 'Empty request body'], 400);
        }
        $this->validateLokasi($request);

        $lokasi = new Lokasi();
        $lokasi->kode_lokasi = $request->input('kode_lokasi');
        $lokasi->nama_lokasi = $request->input('nama_lokasi');
        $lokasi->save();

        return response()->json($lokasi, 201);
    }

    public function update(Request $request, $id)
    {
        if (empty($request->all())) {
            return response()->json(['error' => 'Empty request body'], 400);
        }
        $this->validateLokasi($request);

        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return response()->json(['error' => 'Lokasi not found'], 404);
        }

        $lokasi->kode_lokasi = $request->input('kode_lokasi');
        $lokasi->nama_lokasi = $request->input('nama_lokasi');
        $lokasi->save();

        return response()->json($lokasi, 200);
    }

    public function destroy($id)
    {
        $lokasi = Lokasi::find($id);

        if (!$lokasi) {
            return response()->json(['error' => 'Lokasi not found'], 404);
        }

        $lokasi->delete();

        return response()->json(['message' => 'Lokasi deleted successfully'], 200);
    }

    private function validateLokasi(Request $request)
    {
        $rules = [
            'kode_lokasi' => 'required|max:10',
            'nama_lokasi' => 'nullable|max:255',
        ];

        $this->validate($request, $rules);
    }
}
