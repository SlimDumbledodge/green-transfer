<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfer_id',
        'original_name',
        'stored_path',
        'size',
        'mime_type',
    ];
};
