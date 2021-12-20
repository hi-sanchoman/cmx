<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Point
 * @package App\Models
 * @version October 27, 2021, 6:18 am UTC
 *
 * @property integer $subpolygon_id
 * @property string $lat
 * @property string $lon
 */
class Point extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'points';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'polygon_id',
        'lat',
        'lon',
        'num',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        // 'polygon_id' => 'integer',
        'lat' => 'string',
        'lon' => 'string',
        'num' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        // 'polygon_id' => 'required',
        'lat' => 'required',
        'lon' => 'required',
        'num' => 'required',
    ];

    public function polygon() {
        return $this->belongsTo(Polygon::class);
    }

    public function sample() {
        return $this->hasOne(Sample::class);
    }

    public function qrcode() {
        return $this->hasOne(Qrcode::class);
    }



    public static function dropdownForSample() {
        $res = [];

        $points = Point::with(['polygon', 'polygon.field', 'polygon.field.client'])->get();
        // dd($points->toArray());

        foreach ($points as $point) {
            if ($point->polygon == null || $point->polygon->field == null || $point->polygon->field->client == null) {
                continue;
            }

            $res[$point->id] = 'Метка №' . $point->num . ' (Поле №' . $point->polygon->field->num . ', ' . $point->polygon->field->client->khname . ' )';
        }
        
        return $res;
    }
}
