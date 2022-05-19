<?php

namespace App\Repositories;

use App\Interfaces\TransaksiInterface;
use App\Models\Transaksi;

class TransaksiRepository implements TransaksiInterface
{
  public function getTransaksi($keyword)
  {
    if ($keyword === '')
      $data = Transaksi::with('pelanggan:id,nama,nomor_hp')
        ->orderBy("created_at", "desc");
    else
      $data = Transaksi::with('pelanggan:id,nama,nomor_hp')
        ->where('keluhan', 'ilike', "%{$keyword}%")
        ->orWhere('nama_obat', 'ilike', "%{$keyword}%")
        ->orderBy("created_at", "desc");


    return $data->get();
  }

  public function getByIdPelanggan($id)
  {
    return Transaksi::with('pelanggan:id,nama,nomor_hp')
      ->where('id_pelanggan', $id)
      ->get();
  }

  public function create($data)
  {
    return Transaksi::create($data);
  }
}
