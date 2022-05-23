<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\TransaksiInterface;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    private $transaksi;
    public function __construct(TransaksiInterface $transaksi)
    {
        $this->transaksi = $transaksi;
    }

    public function getTransaksi(Request $request)
    {
        $keyword = $request->query('keyword', '');

        $data = $this->transaksi->getTransaksi($keyword);
        $newData = [];

        foreach ($data as $value) {
            if ($value->pelanggan != null) {
              $newData[] = $value;
            }
        }

        return $this->formatResponse(
            'Berhasil mengambil seluruh transaksi',
            $newData,
            200
        );
    }

    public function getTransaksiById($id)
    {
        $transaksi = $this->transaksi->getByIdPelanggan($id);
        if (!$transaksi) {
            return $this->formatResponse(
                'Transaksi tidak ditemukan',
                null,
                404
            );
        }

        return $this->formatResponse(
            'Berhasil mengambil transaksi',
            $transaksi,
            200
        );
    }

    public function create($id, Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            "keluhan" => "string",
            "saran" => "string",
            "nama_obat" => "required|string",
            "alergi" => "string",
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                "Validasi gagal",
                $validator->errors(),
                422
            );
        }

        $data['id_pelanggan'] = $id;

        $transaksi = $this->transaksi->create($data);

        return $this->formatResponse(
            'Transaksi berhasil ditambahkan',
            $transaksi,
            201
        );
    }
}
