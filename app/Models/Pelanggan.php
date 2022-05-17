<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nama', 'nomor_hp', 'alamat', 'id_pengurus'
    ];

    protected $hidden = ['deleted_at'];

    public function pengurusApotek() 
    {
        return $this->belongsTo(PengurusApotek::class, 'id_pengurus');
    }

    public function transaksi() 
    {
        return $this->hasMany(Transaksi::class, 'id_pelanggan');
    }
}
