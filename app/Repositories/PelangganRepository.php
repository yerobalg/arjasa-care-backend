<?php

use App\Interfaces\PelangganInterface;
use App\Models\Pelanggan;

class PelangganRepository implements PelangganInterface
{
  public function getPelanggan($page, $keyword)
  {
    $offset = ($page - 1) * 10;

    if ($keyword != '') {
      $keyword = strtolower($keyword);
      $pelanggan = Pelanggan::where(
        'lower(nama)',
        'like',
        '%' . $keyword . '%'
      )
        ->orWhere('lower(nomor_hp)', 'like', '%' . $keyword . '%')
        ->offset($offset)->limit(10)->get();
      $total = Pelanggan::where(
        'lower(nama)',
        'like',
        '%' . $keyword . '%'
      )
        ->orWhere('lower(nomor_hp)', 'like', '%' . $keyword . '%')
        ->count();
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

  public function getByNomorHP($nomorHp)
  {
    return Pelanggan::where('nomor_hp', $nomorHp)->first();
  }

  public function create($data)
  {
    return Pelanggan::create($data);
  }

  public function update(Pelanggan $pelanggan, $data) {
    $pelanggan->update($data);
    return $pelanggan;
  }

  public function delete(Pelanggan $pelanggan) 
  {
    $pelanggan->delete();
  }
}
