<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Database\Factories\PaymentTokenFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentToken newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentToken newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentToken query()
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentToken whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentToken whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|PaymentToken whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PaymentToken extends Model
{
    use HasFactory;
}
