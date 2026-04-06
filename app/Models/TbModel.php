<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TbModel extends Model
{
    protected $fillable = ['name', 'filename', 'version', 'description', 'is_active', 'uploaded_by'];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function scanResults()
    {
        return $this->hasMany(ScanResult::class);
    }
}
