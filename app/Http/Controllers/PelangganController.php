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
        $page = $request->query('page', 1);
        $keyword = $request->query('keyword', '');

        $data = $this->pelanggan->getPelanggan($page, $keyword);

        return $this->formatResponse(
            'Berhasil mengambil pelanggan',
            $data,
            200
        );
    }

    public function getPellangan($id) {
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
            "nomor_hp" => "required|string|unique:pelanggan|min:10|max:13|numeric",
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

    public function update (Request $request) {
        $data = $request->all();

        $validator = Validator::make($data, [
            "nama" => "required|string",
            "nomor_hp" => "required|string|min:10|max:13|numeric",
            "alamat" => "required|string",
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                "Validasi gagal",
                $validator->errors(),
                422
            );
        }

        $pelanggan = $this->pelanggan->getById($request->id);
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

    
}