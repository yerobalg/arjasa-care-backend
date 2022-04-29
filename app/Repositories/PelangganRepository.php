<?php

namespace App\Repositories;

use App\Interfaces\PelangganInterface;
use App\Models\Pelanggan;
use App\Illuminate\Support\Facades\DB;

class PelangganRepository implements PelangganInterface
{
  public function getPelanggan($page, $keyword)
  {
    $offset = ($page - 1) * 10;

    if ($keyword != '') {
      $keyword = strtolower($keyword);
      $pelanggan =Pelanggan::whereRaw(
        "lower(nama) like '%$keyword%'"
      )->orWhereRaw(
        "nomor_hp like '%$keyword%'"
      )->offset($offset)->limit(10)->get();
      $total = Pelanggan::whereRaw(
        "lower(nama) like '%$keyword%'"
      )->orWhereRaw(
        "nomor_hp like '%$keyword%'"
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
    return Pelanggan::find($id);
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
