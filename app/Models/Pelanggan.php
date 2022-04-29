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
        'nama', 'nomor_hp', 'alamat'
    ];

    protected $hidden = ['deleted_at'];
}
