<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ElectionType extends Model
{
    protected $fillable = ['name', 'slug'];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }
}