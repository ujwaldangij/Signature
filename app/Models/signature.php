<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class signature extends Model
{
    use HasFactory;
    protected $table = "signatures";
    protected $primaryKey = "id";
    protected $fillable = [
        'username',
        'contact',
        'HcpDesignation',
        'upload_report',
        'HospitalName',
        'specialty',
        'city',
        'report',
        'esign', 
        'ai', 
        'OtherHcpDesignation', 
        'other', 
        'created_at',
        'updated_at',
    ];
}
