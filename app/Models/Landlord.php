<?php

namespace App\Models;

use App\Models\States\LandlordKycStatus;
use App\Models\States\LandlordStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Landlord extends Model
{
    /** @use HasFactory<\Database\Factories\LandlordFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'contact_name',
        'contact_phone',
        'contact_email',
        'status',
        'kyc_status',
        'verification_notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'status' => LandlordStatus::class,
            'kyc_status' => LandlordKycStatus::class,
        ];
    }
}
