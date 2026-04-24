<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'user_id',
        'candidate_id',
        'dpr_candidate_id',
        'election_type_id',
    ];

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function dprCandidate()
    {
        return $this->belongsTo(DprCandidate::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function electionType()
    {
        return $this->belongsTo(ElectionType::class);
    }
}