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
        $page = (int) $request->query('page', 1);
        $keyword = $request->query('keyword', '');

        $data = $this->transaksi->getTransaksi($page);

        return $this->formatResponse(
            'Berhasil mengambil seluruh transaksi',
            $data,
            200
        );
    }

    public function getTransaksiById($id)
    {
        $transaksi = $this->transaksi->getById($id);
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
            "keluhan" => "required|string",
            "saran" => "required|string",
            "nama_obat" => "required|string",
            "alergi" => "required|string",
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

    public function update($id, Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            "keluhan" => "required|string",
            "saran" => "required|string",
            "nama_obat" => "required|string",
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                "Validasi gagal",
                $validator->errors(),
                422
            );
        }

        $transaksi = $this->transaksi->getById($id);
        if (!$transaksi)
            return $this->formatResponse(
                "Transaksi tidak ditemukan",
                null,
                404
            );

        $transaksi = $this->transaksi->update($transaksi, $data);

        return $this->formatResponse(
            'Transaksi berhasil diubah',
            $transaksi,
            200
        );
    }

    public function delete($id)
    {
        $transaksi = $this->transaksi->getById($id);
        if (!$transaksi) {
            return $this->formatResponse(
                'Transaksi tidak ditemukan',
                null,
                404
            );
        }

        $this->transaksi->delete($transaksi);

        return $this->formatResponse(
            'Transaksi berhasil dihapus',
            null,
            200
        );
    }
}
