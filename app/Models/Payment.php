<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $table = 'Payments';
    protected $primaryKey = 'id';

    public const CREATED_AT = 'createdAt';
    public const UPDATED_AT = 'updatedAt';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'txn_id',
        'payment_gross',
        'currency_code',
        'payer_email',
        'payment_status',
        'PackageId',
        'UserId'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId');
    }
    public function package()
    {
        return $this->belongsTo(Package::class, 'PackageId');
    }
}
 