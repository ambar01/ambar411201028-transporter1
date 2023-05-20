<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Barang;

class BarangController extends Controller
{
    public function index()
    {
        $barangs = Barang::all();
        return response()->json($barangs, 200);
    }

    public function show($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(['error' => 'Barang not found'], 404);
        }

        return response()->json($barang, 200);
    }

    public function store(Request $request)
    {
        if (empty($request->all())) {
            return response()->json(['error' => 'Empty request body'], 400);
        }
        $this->validateBarang($request);

        $barang = new Barang();
        $barang->kode_barang = $request->input('kode_barang');
        $barang->nama_barang = $request->input('nama_barang');
        $barang->deskripsi = $request->input('deskripsi');
        $barang->stok_barang = $request->input('stok_barang');
        $barang->harga_barang = $request->input('harga_barang');
        $barang->save();

        return response()->json($barang, 201);
    }

    public function update(Request $request, $id)
    {
        if (empty($request->all())) {
            return response()->json(['error' => 'Empty request body'], 400);
        }
        $this->validateBarang($request);

        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(['error' => 'Barang not found'], 404);
        }

        $barang->kode_barang = $request->input('kode_barang');
        $barang->nama_barang = $request->input('nama_barang');
        $barang->deskripsi = $request->input('deskripsi');
        $barang->stok_barang = $request->input('stok_barang');
        $barang->harga_barang = $request->input('harga_barang');
        $barang->save();

        return response()->json($barang, 200);
    }

    public function destroy($id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json(['error' => 'Barang not found'], 404);
        }

        $barang->delete();

        return response()->json(['message' => 'Barang deleted successfully'], 200);
    }

    private function validateBarang(Request $request)
    {
        $rules = [
            'kode_barang' => 'required|max:10',
            'nama_barang' => 'nullable|max:200',
            'deskripsi' => 'nullable',
            'stok_barang' => 'nullable|integer',
            'harga_barang' => 'nullable|integer',
        ];

        $this->validate($request, $rules);
    }
}
