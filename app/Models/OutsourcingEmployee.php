<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutsourcingEmployee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'position',
        'contract_start',
        'contract_end',
        'contract_number',
    ];

    protected $casts = [
        'contract_start' => 'date',
        'contract_end'   => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isContractActive(): bool
    {
        return now()->between($this->contract_start, $this->contract_end);
    }
}
