<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'fecha', 'sala_id'];

    public function sala()
    {
        return $this->belongsTo(Sala::class);
    }
}
