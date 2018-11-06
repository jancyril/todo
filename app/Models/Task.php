<?php

/**
 * @author Jan Cyril Segubience <jancyril@segubience.com>
 */
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title', 'status', 'description'
    ];
}
