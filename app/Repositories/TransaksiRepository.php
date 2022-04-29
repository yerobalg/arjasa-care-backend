<?php

namespace App\Repositories;

//use App\Interfaces\PelangganInterface;
use App\Models\Transaksi;

class TransaksiRepository
{
  public function getTransaksi($page)
  {
    $offset = ($page - 1) * 25;
    $data = Transaksi::orderBy("created_at", "desc")
      ->offset($offset)->limit(25)->get();

    $total = Transaksi::orderBy("created_at", "desc")
      ->offset($offset)->limit(25)->count();

    return [
      'data' => $data,
      'currentPage' => $page,
      'totalPage' => ceil($total / 25),
      'totalData' => $total
    ];
  }

  public function getById($id)
  {
    return Transaksi::find($id);
  }

  public function create($data)
  {
    return Transaksi::create($data);
  }

  public function update(Transaksi $transaksi, $data)
  {
    $transaksi->update($data);
    return $transaksi;
  }

  public function delete(Transaksi $transaksi)
  {
    $transaksi->delete();
  }
}
