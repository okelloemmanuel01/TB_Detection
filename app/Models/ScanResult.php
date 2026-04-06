<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScanResult extends Model
{
    protected $fillable = ['user_id', 'tb_model_id', 'xray_image', 'heatmap_image', 'result', 'confidence'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tbModel()
    {
        return $this->belongsTo(TbModel::class);
    }
}
