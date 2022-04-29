<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\PelangganInterface;
use Illuminate\Support\Facades\Validator;


class PelangganController extends Controller 
{
    private $pelanggan;
    public function __construct(PelangganInterface $pelanggan)
    {
        $this->pelanggan = $pelanggan;
    }

    public function getPelanggan(Request $request)
    {
        $page = (int) $request->query('page', 1);
        $keyword = $request->query('keyword', '');

        $data = $this->pelanggan->getPelanggan($page, $keyword);

        return $this->formatResponse(
            'Berhasil mengambil pelanggan',
            $data,
            200
        );
    }

    public function getPelangganById($id) {
        $pelanggan = $this->pelanggan->getById($id);
        if (!$pelanggan) {
            return $this->formatResponse(
                'Pelanggan tidak ditemukan',
                null,
                404
            );
        }

        return $this->formatResponse(
            'Berhasil mengambil pelanggan',
            $pelanggan,
            200
        );
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            "nama" => "required|string",
            "nomor_hp" => "required|string|unique:pelanggan|numeric|digits_between:10,15",
            "alamat" => "required|string",
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                "Validasi gagal",
                $validator->errors(),
                422
            );
        }

        $pelanggan = $this->pelanggan->create($data);

        return $this->formatResponse(
            'Pelanggan berhasil ditambahkan',
            $pelanggan,
            201
        );
    }

    public function update ($id, Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            "nama" => "required|string",
            "nomor_hp" => "required|string|numeric|digits_between:10,15",
            "alamat" => "required|string",
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                "Validasi gagal",
                $validator->errors(),
                422
            );
        }

        $pelanggan = $this->pelanggan->getById($id);
        if(!$pelanggan) 
            return $this->formatResponse(
                "Pelanggan tidak ditemukan",
                null,
                404
            );
        
        $pelanggan = $this->pelanggan->update($pelanggan, $data);

        return $this->formatResponse(
            'Pelanggan berhasil diubah',
            $pelanggan,
            200
        );
    }

    public function delete ($id) {
        $pelanggan = $this->pelanggan->getById($id);
        if (!$pelanggan) {
            return $this->formatResponse(
                'Pelanggan tidak ditemukan',
                null,
                404
            );
        }

        $this->pelanggan->delete($pelanggan);

        return $this->formatResponse(
            'Pelanggan berhasil dihapus',
            null,
            200
        );
    }
}
