<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Subpolygon
 * @package App\Models
 * @version October 27, 2021, 6:17 am UTC
 *
 * @property integer $polygon_id
 * @property string $geometry
 */
class Subpolygon extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'subpolygons';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'polygon_id',
        'geometry'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'polygon_id' => 'integer',
        'geometry' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'polygon_id' => 'required',
        'geometry' => 'required'
    ];

    
}
