<?php

namespace App\Interfaces;
use App\Models\Transaksi;

interface PelangganInterface {
  public function getById($id);
  public function create($data);
  public function update(Transaksi $transaksi, $data);
  public function delete(Transaksi $transaksi);
  public function getTransaksi($page);
}
