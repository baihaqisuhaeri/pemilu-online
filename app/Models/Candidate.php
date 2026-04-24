<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'wakil_name',
        'photo',
        'visi_misi',
        'nomor_urut',
        'election_type_id',
    ];

    public function votes()
{
    return $this->hasMany(Vote::class);
}

public function electionType()
    {
        return $this->belongsTo(ElectionType::class);
    }
    
}