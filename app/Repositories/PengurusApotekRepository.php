<?php

namespace App\Repositories;

use App\Interfaces\PengurusApotekInterface;
use App\Models\PengurusApotek;

class PengurusApotekRepository implements PengurusApotekInterface
{
  public function getAll()
  {
    return PengurusApotek::all();
  }

  public function getByUsername($username)
  {
    return PengurusApotek::where('username', $username)->first();
  }

  public function create($data)
  {
    return PengurusApotek::create($data);
  }

  public function updateProfil(PengurusApotek $pengurus, $data)
  {
    $pengurus->update($data);
    return $pengurus;
  }

  public function delete(PengurusApotek $pengurus)
  {
    $pengurus->delete();
  }

  public function getKaryawan($page)
  {
    $offset = ($page - 1) * 10;
    $data = PengurusApotek::where('is_karyawan', 1)
      ->offset($offset)->limit(10)->get();

    $total = PengurusApotek::where('is_karyawan', 1)->count();

    return [
      'data' => $data,
      'currentPage' => $page,
      'totalPage' => ceil($total / 10),
      'totalData' => $total
    ];
  }
}
