<?php

namespace App\Interfaces;
use App\Models\Transaksi;

interface TransaksiInterface {
  public function getByIdPelanggan($id);
  public function create($data);
  public function getTransaksi($keyword);
}
