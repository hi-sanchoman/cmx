<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Qrcode
 * @package App\Models
 * @version October 27, 2021, 6:19 am UTC
 *
 * @property integer $point_id
 * @property string $content
 */
class Qrcode extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'qrcodes';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'point_id',
        'content'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'point_id' => 'integer',
        'content' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'point_id' => 'required'
    ];

    
    public function point() {
        return $this->belongsTo(Point::class);
    }
}
