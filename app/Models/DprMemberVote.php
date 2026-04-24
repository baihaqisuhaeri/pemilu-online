<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DprMemberVote extends Model
{
    protected $fillable = [
        'user_id',
        'dpr_candidate_id',
        'dpr_member_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dprCandidate()
    {
        return $this->belongsTo(DprCandidate::class);
    }

    public function dprMember()
    {
        return $this->belongsTo(DprMember::class);
    }
}