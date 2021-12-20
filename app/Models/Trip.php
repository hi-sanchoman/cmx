<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Trip
 * @package App\Models
 * @version October 27, 2021, 4:15 am UTC
 *
 * @property string $date
 * @property string $status
 * @property integer $field_id
 * @property string $date_completed
 */
class Trip extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'trips';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'date',
        'status',
        'field_id',
        'date_completed'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'status' => 'string',
        'field_id' => 'integer'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'date' => 'required',
        'status' => 'required',
        'field_id' => 'required'
    ];

    public function field() {
        return $this->belongsTo(Field::class);
    }
}
