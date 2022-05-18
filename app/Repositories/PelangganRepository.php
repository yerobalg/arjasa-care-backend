<?php

namespace App\Repositories;

use App\Interfaces\PelangganInterface;
use App\Models\Pelanggan;

class PelangganRepository implements PelangganInterface
{
  public function getPelanggan($page, $keyword)
  {
    $offset = ($page - 1) * 10;

    if ($keyword != '') {
      $pelanggan = Pelanggan::with('pengurusApotek:id,username')
        ->where(
          'nama',
          'ilike',
          "%{$keyword}%"
        )
        ->orWhere(
          'nomor_hp',
          'ilike',
          "%{$keyword}%"
        )->offset($offset)->limit(10)->get();
      $total =  $pelanggan = Pelanggan::with('pengurusApotek:id,username')
        ->where(
          'nama',
          'ilike',
          "%{$keyword}%"
        )
        ->orWhere(
          'nomor_hp',
          'ilike',
          "%{$keyword}%"
        )->count();
    } else {
      $pelanggan = Pelanggan::offset($offset)->limit(10)->get();
      $total = Pelanggan::count();
    }

    return [
      'data' => $pelanggan,
      'currentPage' => $page,
      'totalPage' => ceil($total / 10),
      'totalData' => $total
    ];
  }

  public function getById($id)
  {
    return Pelanggan::with('pengurusApotek:id,username')
      ->with('transaksi')
      ->find($id);
  }

  public function create($data)
  {
    return Pelanggan::create($data);
  }

  public function update(Pelanggan $pelanggan, $data)
  {
    $pelanggan->update($data);
    return $pelanggan;
  }

  public function delete(Pelanggan $pelanggan)
  {
    $pelanggan->delete();
  }
}
