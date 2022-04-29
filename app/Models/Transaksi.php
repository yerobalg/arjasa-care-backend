<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Transaksi extends Model
{
    protected $table = 'transaksi_pelanggan';
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'keluhan', 'saran', 'nama_obat'
    ];

    protected $hidden = ['deleted_at'];
}
