<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Task
 */
class Task extends Model
{
    use SoftDeletes, HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'due_date',
        'status',
        'user_id',
        'position',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];
}
