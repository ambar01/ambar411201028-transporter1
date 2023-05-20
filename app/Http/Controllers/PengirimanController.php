<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pengiriman;
use Illuminate\Support\Facades\Auth;

class PengirimanController extends Controller
{
    public function index()
    {
        $pengiriman = Pengiriman::all();
        return response()->json($pengiriman, 200);
    }

    public function show($id)
    {
        $pengiriman = Pengiriman::find($id);

        if (!$pengiriman) {
            return response()->json(['error' => 'Pengiriman not found'], 404);
        }

        return response()->json($pengiriman, 200);
    }

    public function store(Request $request)
    {
        if (empty($request->all())) {
            return response()->json(['error' => 'Empty request body'], 400);
        }
        $this->validatePengiriman($request);

        $pengiriman = new Pengiriman();
        $pengiriman->no_pengiriman = $request->input('no_pengiriman');
        $pengiriman->tanggal = $request->input('tanggal');
        $pengiriman->lokasi_id = $request->input('lokasi_id');
        $pengiriman->barang_id = $request->input('barang_id');
        $pengiriman->jumlah_barang = $request->input('jumlah_barang');
        $pengiriman->harga_barang = $request->input('harga_barang');
        $pengiriman->kurir_id = $request->input('kurir_id');
        $pengiriman->save();

        return response()->json($pengiriman, 201);
    }

    public function update(Request $request, $id)
    {
        if (empty($request->all())) {
            return response()->json(['error' => 'Empty request body'], 400);
        }
        $this->validatePengiriman($request);

        $pengiriman = Pengiriman::find($id);

        if (!$pengiriman) {
            return response()->json(['error' => 'Pengiriman not found'], 404);
        }

        $pengiriman->kode_lokasi = $request->input('kode_lokasi');
        $pengiriman->nama_lokasi = $request->input('nama_lokasi');
        $pengiriman->save();

        return response()->json($pengiriman, 200);
    }

    public function destroy($id)
    {
        $pengiriman = Pengiriman::find($id);

        if (!$pengiriman) {
            return response()->json(['error' => 'Pengiriman not found'], 404);
        }

        $pengiriman->delete();

        return response()->json(['message' => 'Pengiriman deleted successfully'], 200);
    }

    private function validatePengiriman(Request $request)
    {
        $rules = [
            'no_pengiriman' => 'required',
            'tanggal' => 'required|date',
            'lokasi_id' => 'required|exists:lokasis,id',
            'barang_id' => 'required|exists:barangs,id',
            'jumlah_barang' => 'required|integer',
            'harga_barang' => 'integer',
            'kurir_id' => 'integer',
        ];

        $this->validate($request, $rules);
    }

    public function submitPengiriman(Request $request)
    {
        $kurirId = Auth::guard('kurir')->user()->id;

        $pengiriman = new Pengiriman;
        $pengiriman->no_pengiriman = $request->input('no_pengiriman');
        $pengiriman->tanggal = $request->input('tanggal');
        $pengiriman->lokasi_id = $request->input('lokasi_id');
        $pengiriman->barang_id = $request->input('barang_id');
        $pengiriman->jumlah_barang = $request->input('jumlah_barang');
        $pengiriman->harga_barang = $request->input('harga_barang');
        $pengiriman->kurir_id = $kurirId;
        $pengiriman->save();

        // Return a response indicating the success of the creation
        return response()->json(['message' => 'Selamat anda berhasil submit Pengiriman']);
    }

    public function approvePengiriman(Request $request, $no_pengiriman)
    {
        $pengiriman = Pengiriman::where('no_pengiriman', $no_pengiriman)->first();

        if (!$pengiriman) {
            return response()->json(['message' => 'Data Pengiriman not found'], 404);
        }

        $pengiriman->status = $request->input('status');
        $pengiriman->save();

        // Return a response indicating the success of the update
        return response()->json(['message' => 'Pengiriman successfully approved']);
    }
}
