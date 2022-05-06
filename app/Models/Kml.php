<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Kml
 * @package App\Models
 * @version October 27, 2021, 6:13 am UTC
 *
 * @property string $path
 * @property string $content
 * @property integer $field_id
 */
class Kml extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'kmls';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'path',
        'content',
        'field_id'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'path' => 'string',
        'content' => 'string',
        'field_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'path' => 'required',
        'content' => 'required',
        'field_id' => 'required'
    ];

    
}
