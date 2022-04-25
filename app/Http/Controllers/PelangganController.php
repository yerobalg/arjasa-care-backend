<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\PelangganInterface;

class PelangganController extends Controller 
{
    private $pelanggan;
    public function __construct(PelangganInterface $pelanggan)
    {
        $this->pelanggan = $pelanggan;
    }

    public function getPelanggan(Request $request)
    {
        $page = $request->query('page', 1);
        $keyword = $request->query('keyword', '');

        $data = $this->pelanggan->getPelanggan($page, $keyword);

        return $this->formatResponse(
            'Berhasil mengambil data pelanggan',
            $data,
            200
        );
    }

    
}