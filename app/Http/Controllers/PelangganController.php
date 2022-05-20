<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\PelangganInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;



class PelangganController extends Controller
{
    private $pelanggan;
    public function __construct(PelangganInterface $pelanggan)
    {
        $this->pelanggan = $pelanggan;
    }

    private function tandaTanganFormatter($ttd, $host)
    {
        return "{$host}/public/images/tanda-tangan/{$ttd}";
    }

    public function getPelanggan(Request $request)
    {
        $page = (int) $request->query('page', 1);
        $keyword = $request->query('keyword', '');

        $data = $this->pelanggan->getPelanggan($page, $keyword);

        foreach ($data["data"] as $value)
            $value->tanda_tangan = $this
                ->tandaTanganFormatter(
                    $value->tanda_tangan,
                    $request->getSchemeAndHttpHost()
                );

        return $this->formatResponse(
            'Berhasil mengambil pelanggan',
            $data,
            200
        );
    }

    public function getPelangganById($id, Request $request)
    {
        $pelanggan = $this->pelanggan->getById($id);
        if (!$pelanggan) {
            return $this->formatResponse(
                'Pelanggan tidak ditemukan',
                null,
                404
            );
        }

        if ($pelanggan->tanda_tangan)
            $pelanggan->tanda_tangan = $this
                ->tandaTanganFormatter(
                    $pelanggan->tanda_tangan,
                    $request->getSchemeAndHttpHost()
                );

        return $this->formatResponse(
            'Berhasil mengambil pelanggan',
            $pelanggan,
            200
        );
    }

    public function create(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, [
            "nama" => "required|string",
            "nomor_hp" => "required|string|unique:pelanggan|numeric|digits_between:10,15",
            "alamat" => "required|string",
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                "Validasi gagal",
                $validator->errors(),
                422
            );
        }

        $data['id_pengurus'] = auth()->user()->id;

        $pelanggan = $this->pelanggan->create($data);

        if ($request->hasFile('tanda_tangan')) {
            $file = $request->file('tanda_tangan');

            $allowedExtensions = ["jpg", "jpeg", "png"];
            $fileExtension = $file->getClientOriginalExtension();

            if (!in_array($fileExtension, $allowedExtensions))
                return $this->formatResponse(
                    "File harus berupa gambar dengan ekstensi jpg, jpeg, atau png",
                    null,
                    422
                );

            $name = time() . "_{$pelanggan['id']}." . $fileExtension;
            $file->move('tanda-tangan', $name);

            $data['tanda_tangan'] = $name;
            $pelanggan = $this->pelanggan->update($pelanggan, $data);
        }

        if ($pelanggan['tanda_tangan'])
            $pelanggan['tanda_tangan'] =  $this
                ->tandaTanganFormatter(
                    $pelanggan->tanda_tangan,
                    $request->getSchemeAndHttpHost()
                );

        return $this->formatResponse(
            'Pelanggan berhasil ditambahkan',
            $pelanggan,
            201
        );
    }

    public function update($id, Request $request)
    {

        $pelanggan = $this->pelanggan->getById($id);
        if (!$pelanggan)
            return $this->formatResponse(
                "Pelanggan tidak ditemukan",
                null,
                404
            );


        $data = $request->all();

        if ($data["nomor_hp"] == $pelanggan["nomor_hp"])
            unset($data["nomor_hp"]);

        $validator = Validator::make($data, [
            "nama" => "required|string",
            "nomor_hp" => "unique:pelanggan|string|numeric|digits_between:10,15",
            "alamat" => "required|string",
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                "Validasi gagal",
                $validator->errors(),
                422
            );
        }



        if ($request->hasFile('tanda_tangan')) {
            $file = $request->file('tanda_tangan');

            $allowedExtensions = ["jpg", "jpeg", "png"];
            $fileExtension = $file->getClientOriginalExtension();
            $fileExtension = pathinfo($fileExtension, PATHINFO_FILENAME);

            if (!in_array($fileExtension, $allowedExtensions))
                return $this->formatResponse(
                    "File harus berupa gambar dengan ekstensi jpg, jpeg, atau png",
                    null,
                    422
                );

            $name = time() . "_{$pelanggan['id']}." . $fileExtension;
            $file->storeAs('tanda_tangan', $name);

            $data['tanda_tangan'] = $name;
        }

        $data['id_pengurus'] = auth()->user()->id;

        $pelanggan = $this->pelanggan->update($pelanggan, $data);

        if ($pelanggan['tanda_tangan'])
            $pelanggan['tanda_tangan'] = $this
                ->tandaTanganFormatter(
                    $pelanggan['tanda_tangan'],
                    $request->getSchemeAndHttpHost()
                );

        return $this->formatResponse(
            'Pelanggan berhasil diubah',
            $pelanggan,
            200
        );
    }

    public function delete($id)
    {
        $pelanggan = $this->pelanggan->getById($id);
        if (!$pelanggan) {
            return $this->formatResponse(
                'Pelanggan tidak ditemukan',
                null,
                404
            );
        }

        $this->pelanggan->delete($pelanggan);

        return $this->formatResponse(
            'Pelanggan berhasil dihapus',
            null,
            200
        );
    }
}
