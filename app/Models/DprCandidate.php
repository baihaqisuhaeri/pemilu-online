<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DprCandidate extends Model
{
    protected $fillable = [
        'nomor_urut',
        'nama_partai',
        'logo_partai',
    ];

    public function members()
    {
        return $this->hasMany(DprMember::class)->orderBy('nomor_urut');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function memberVotes()
    {
        return $this->hasMany(DprMemberVote::class);
    }
}