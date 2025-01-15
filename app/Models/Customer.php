<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 *     schema="Customer",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="first_name", type="string", example="John"),
 *     @OA\Property(property="last_name", type="string", example="Doe"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="age", type="integer", example=30),
 *     @OA\Property(property="dob", type="string", format="date", example="1993-05-20")
 * )
 */
class Customer extends Model
{
    use HasFactory;
    
    protected $table = 'customers';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'age',
        'dob',
    ];
}
