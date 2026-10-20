<?php

namespace App\Models;

use Database\Factories\CompanyFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;

#[Fillable(['name', 'email', 'logo', 'website'])]

class Company extends Model
{
    /** @use HasFactory<CompanyFactory> */
    use HasFactory;

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    protected function logoUrl(): Attribute
    {
        return Attribute::get(
            fn () => $this->logo ? asset('storage/' . $this->logo) : null
        );
    }
}
