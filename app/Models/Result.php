<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Result
 * @package App\Models
 * @version October 27, 2021, 6:30 am UTC
 *
 * @property integer $sample_id
 * @property string $passed
 * @property string $accepted
 * @property number $value1
 * @property number $value2
 * @property number $value3
 * @property number $value4
 * @property number $value5
 * @property number $value6
 * @property number $value7
 * @property number $value8
 * @property number $value9
 * @property number $value10
 * @property number $value11
 * @property number $value12
 * @property number $value13
 */
class Result extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'results';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'sample_id',
        'passed',
        'accepted',
        'humus',
        'p',
        'no3',
        's',
        'k',
        'ph',
        'b',
        'fe',
        'salinity',
        'absorbed_sum',
        'mn',
        'zn',
        'cu',
        'calcium',
        'magnesium',
        'na',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'sample_id' => 'integer',
        'passed' => 'string',
        'accepted' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'sample_id' => 'required',
        'passed' => 'required',
        'accepted' => 'required',
        'ph' => 'required',
        'no3' => 'required',
        'humus' => 'required',
        's' => 'required',
        'k' => 'required',
        'p' => 'required'
    ];

    

    public function sample() {
        return $this->belongsTo(Sample::class);
    }
}
