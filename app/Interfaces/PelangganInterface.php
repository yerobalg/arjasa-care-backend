<?php

namespace App\Interfaces;
use App\Models\Pelanggan;

interface PelangganInterface {
  public function getByNomorHP($nomorHp);
  public function create($data);
  public function update(Pelanggan $pelanggan, $data);
  public function delete(Pelanggan $pelanggan);
  public function getPelanggan($page, $keyword);
}
