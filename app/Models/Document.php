<?php

namespace App\Models;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Document extends Model
{
    use HasFactory;

    // protected $uploads = '/documents/';

    protected $guarded = [];

    public function customers()
    {
        return $this->belongsTo(Customer::class);
    }

    // public function getFileAttribute($photo)
    // {

    //     return $this->uploads . $photo;
    // }
}
