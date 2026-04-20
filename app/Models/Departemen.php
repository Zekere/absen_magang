<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departemen extends Model
{
    protected $table      = 'departemen';
    protected $primaryKey = 'kode_dept';
    public    $timestamps = false;

    protected $fillable = ['kode_dept', 'nama_dept'];
}