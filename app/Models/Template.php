<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    use HasFactory;

    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (mixed $value) => $value == 0 ? 'Inactive' : 'Active'
        );
    }

    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'uploaded_by');
    }
}
