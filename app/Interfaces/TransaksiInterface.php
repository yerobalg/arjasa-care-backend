<?php

namespace App\Interfaces;
use App\Models\Transaksi;

interface TransaksiInterface {
  public function getById($id);
  public function create($data);
  public function getTransaksi($keyword);
}
