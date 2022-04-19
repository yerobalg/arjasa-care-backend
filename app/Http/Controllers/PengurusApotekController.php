<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\PengurusApotekInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class PengurusApotekController extends Controller
{
    private $pengurus;
    public function __construct(PengurusApotekInterface $pengurus)
    {
        $this->pengurus = $pengurus;
    }

    public function createKaryawan(Request $request)
    {
        $user = $request->get('karyawan');

        if ($user["is_karyawan"] == 1)
            return $this->unauthorized();

        $data = $request->all();
        $validator = Validator::make($data, [
            "fullname" => "required|string",
            "username" => "required|string|unique:pengurus_apotek|min:5|max:20|regex:/^\S*$/u",
            "password" => [
                'required',
                'string', 'max:32',
                Password::min(8)->letters()->numbers()
            ],
        ]);

        $data['password'] = Hash::make(
            $data['password'],
            ['rounds' => env('PASSWORD_ROUND')]
        );

        $data['is_karyawan'] = true;

        if ($validator->fails()) {
            return $this->formatResponse(
                "Validasi gagal",
                $validator->errors(),
                422
            );
        }

        $karyawan = $this->pengurus->create($data);

        return $this->formatResponse(
            'Karyawan berhasil ditambahkan',
            $karyawan,
            201
        );
    }

    public function login(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            "username" => "required|string",
            "password" => "required|string",
        ]);

        if ($validator->fails())
            return $this->formatResponse(
                "Harap isi semua data",
                $validator->errors(),
                422
            );

        $karyawan = $this->pengurus->getByUsername($data['username']);

        if (!$karyawan)
            return $this->formatResponse(
                "Username tidak ditemukan",
                null,
                404
            );

        if (!Hash::check($data['password'], $karyawan->password))
            return $this->formatResponse(
                "Password salah",
                null,
                401
            );

        $token = auth()->attempt([
            'username' => $data['username'],
            'password' => $data['password']
        ]);

        return $this->formatResponse(
            'Berhasil login',
            ['karyawan' => $karyawan, 'token' => $token],
        );
    }

    public function profil()
    {
        $karyawan = auth()->user();

        return $this->formatResponse(
            'Berhasil mengambil profil',
            $karyawan
        );
    }

    public function updateProfil(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            "fullname" => "required|string",
            "username" => "required|string|min:5|max:20|regex:/^\S*$/u",
        ]);

        if ($validator->fails()) {
            return $this->formatResponse(
                "Validasi gagal",
                $validator->errors(),
                422
            );
        }

        $user = $this->pengurus->updateProfil(auth()->user(), $data);

        return $this->formatResponse(
            "Profil berhasil diubah",
            $user,
            200
        );
    }
}
