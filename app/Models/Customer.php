<?php

namespace App\Models;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    public function branches()
    {
        return $this->belongsTo(Branch::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
