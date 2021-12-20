<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Polygon
 * @package App\Models
 * @version October 27, 2021, 6:15 am UTC
 *
 * @property integer $field_id
 * @property string $geometry
 */
class Polygon extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'polygons';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'field_id',
        'geometry'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'field_id' => 'integer',
        'geometry' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'field_id' => 'required',
        'geometry' => 'required'
    ];

    
    public function field() {
        return $this->belongsTo(Field::class);
    }

    public function points() {
        return $this->hasMany(Point::class);
    }
}
