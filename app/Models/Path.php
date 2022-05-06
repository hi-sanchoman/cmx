<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Path
 * @package App\Models
 * @version December 23, 2021, 3:34 pm +06
 *
 * @property string $date_started
 * @property string $date_completed
 */
class Path extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'paths';
    

    protected $dates = ['deleted_at', 'date_started', 'date_completed'];



    public $fillable = [
        'date_started',
        'date_completed',
        'unit',
        'path'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        
    ];

    
}
