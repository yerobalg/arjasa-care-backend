<?php
namespace App\Interfaces;
use App\Models\PengurusApotek;

interface PengurusApotekInterface {
  public function getAll();
  public function getByUsername($username);
  public function create($data);
  public function updateProfil(PengurusApotek $pengurus, $data);
  public function delete(PengurusApotek $pengurus);
  public function getKaryawan($page);
}
