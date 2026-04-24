<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DprMember extends Model
{
    protected $fillable = [
        'dpr_candidate_id',
        'nomor_urut',
        'nama',
    ];

    public function dprCandidate()
    {
        return $this->belongsTo(DprCandidate::class);
    }

    public function votes()
    {
        return $this->hasMany(DprMemberVote::class);
    }
}