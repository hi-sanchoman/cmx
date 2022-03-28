<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Field
 * @package App\Models
 * @version October 27, 2021, 3:57 am UTC
 *
 * @property string $cadnum
 * @property string $type
 * @property number $square
 * @property string $culture
 * @property string $description
 * @property integer $region_id
 */
class Field extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'fields';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'cadnum',
        'type',
        'square',
        'culture',
        'description',
        'region_id',
        'client_id',
        'num',
        'address',
        'is_selfselection',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'cadnum' => 'string',
        'type' => 'string',
        'square' => 'double',
        'culture' => 'string',
        'description' => 'string',
        'region_id' => 'integer',
        'client_id' => 'integer',
        'num' => 'integer',
        'address' => 'string',
        'is_selfselection' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'cadnum' => 'required',
        'type' => 'required',
        'square' => 'required|numeric',
        'region_id' => 'required',
        'client_id' => 'required',
        'num' => 'required',
        'address' => 'required|min:1',
    ];

    
    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function polygon() {
        return $this->hasOne(Polygon::class);
    }

    public function cartogram() {
        return $this->hasOne(Cartogram::class);
    }


    public static function m2d($arr) {
        // dd(atan(0.75));

        $x = $arr[0];
        $y = $arr[1];
        // dd([$x, $y]);

        $lng = $x *  180 / 20037508.34 ;
        //thanks magichim @ github for the correction
        $lat = atan(exp($y * pi() / 20037508.34)) * 360 / pi() - 90; 
    
        return [$lng, $lat];
    }

    public static function d2m($arr) {
        // dd($arr);

        $x = $arr[0];
        $y = $arr[1];

        $lng = $x * 20037508.34 / 180;
        $lat = log(tan((90 + $y) * pi() / 360)) / (pi() / 180);
        $lat = $lat * 20037508.34 / 180;

        // dd([$arr, [$lng, $lat], self::m2d([$lng, $lat]), tan(2.7183)]);

        return [$lng, $lat];
    }
}
