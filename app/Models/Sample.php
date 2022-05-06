<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Sample
 * @package App\Models
 * @version October 27, 2021, 6:23 am UTC
 *
 * @property integer $point_id
 * @property string $date_selected
 * @property string $date_received
 * @property integer $quantity
 * @property string $passed
 * @property string $accepted
 * @property string $notes
 */
class Sample extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'samples';
    

    protected $dates = [
        'deleted_at', 
        'date_selected', 
        'date_received',
        'date_started',
        'date_completed',
    ];



    public $fillable = [
        'point_id',
        'date_selected',
        'date_received',
        'date_started',
        'date_completed',
        'num',
        'quantity',
        'passed',
        'accepted',
        'notes',
        'p',
        'k',
        's',
        'humus',
        'humus_mass',
        'no3',
        'ph',
        'b',
        'fe',
        'cu',
        'zn',
        'mn',
        'na',
        'calcium',
        'magnesium',
        'salinity',
        'absorbed_sum',
        'na_x2',
        'calcium_v1',
        'calcium_v2',
        'calcium_c',
        'magnesium_v1',
        'magnesium_v2',
        'magnesium_c',
        'absorbed_sum_v',
        'absorbed_sum_m',
        'absorbed_sum_c',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'point_id' => 'integer',
        'quantity' => 'integer',
        'passed' => 'string',
        'accepted' => 'string',
        'notes' => 'string',
        'num' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'point_id' => 'required',
        'date_selected' => 'required',
        'date_received' => 'required',
        'quantity' => 'required|numeric',
        'passed' => 'required',
        'accepted' => 'required',
        'num' => 'required',
    ];


    public function point() {
        return $this->belongsTo(Point::class);
    }

    public function result() {
        return $this->hasOne(Result::class);
    }
    

    public static function dropdownForResult() {
        $res = [];

        $items = Sample::with(['point', 'point.polygon', 'point.polygon.field', 'point.polygon.field.client'])->get();

        foreach ($items as $item) {
            if ($item->point == null || $item->point->polygon == null || $item->point->polygon->field == null || $item->point->polygon->field->client == null) {
                continue;
            }
            // TODO: num for sample !!!
            // $res[$item->id] = 'Метка №' . $item->point->num . ' (Поле №' . $item->point->polygon->field->num . ', ' . $item->point->polygon->field->client->firstname . ' )';
            $res[$item->id] = $item->num . '-П-' . $item->point->polygon->field->client->num . '-' . now()->year;
        }
        
        return $res;
    }



    public static function dropdownQuantity() {
        return [
            'part' => '6 показателей',
            'full' => '16 показателей',
        ];
    }


    public static function graduatePh($value) {
        if ($value < 0) {
            return '-';
        } else if ($value < 4.6) {
            return 'Сильнокислая';
        } else if ($value >= 4.6 && $value < 5.1) {
            return 'Среднекислая';
        } else if ($value >= 5.1 && $value < 5.6) {
            return 'Слабокислая';
        } else if ($value >= 5.6 && $value < 6.1) {
            return 'Близкая к нейтральной';
        } else if ($value >= 6.1 && $value < 7.1) {
            return 'Нейтральная';
        } else if ($value >= 7.1 && $value < 8.0) {
            return 'Слабощелочная';
        } else {
            return 'Щелочная';
        }
    }


    public static function graduateHumus($value) {
        if ($value < 0) {
            return '-';
        } else if ($value < 2.1) {
            return 'Очень низкое';
        } else if ($value >= 2.1 && $value < 4.1) {
            return 'Низкое';
        } else if ($value >= 4.1 && $value < 6.1) {
            return 'Среднее';
        } else if ($value >= 6.1 && $value < 8.0) {
            return 'Повышенное';
        } else if ($value >= 8.1 && $value < 10.0) {
            return 'Высокое';
        } else {
            return 'Очень высокое';
        }
    }

    public static function graduateP($value) {
        if ($value < 0) {
            return '-';
        } else if ($value < 11) {
            return 'Очень низкое';
        } else if ($value >= 11 && $value < 16) {
            return 'Низкое';
        } else if ($value >= 16 && $value < 31) {
            return 'Среднее';
        } else if ($value >= 31 && $value < 45) {
            return 'Повышенное';
        } else if ($value >= 45 && $value < 60) {
            return 'Высокое';
        } else {
            return 'Очень высокое';
        }
    }

    public static function graduateK($value) {
        if ($value < 0) {
            return '-';
        } else if ($value < 101) {
            return 'Очень низкое';
        } else if ($value >= 101 && $value < 201) {
            return 'Низкое';
        } else if ($value >= 201 && $value < 301) {
            return 'Среднее';
        } else if ($value >= 301 && $value < 401) {
            return 'Повышенное';
        } else if ($value >= 401 && $value < 601) {
            return 'Высокое';
        } else {
            return 'Очень высокое';
        }
    } 

    public static function graduateS($value) {
        if ($value < 0) {
            return '-';
        } else if ($value < 6.0) {
            return 'Низкое';
        } else if ($value >= 6.0 && $value < 12.0) {
            return 'Среднее';
        } else {
            return 'Высокое';
        }
    } 

    public static function graduateNo3($value) {
        if ($value < 0) {
            return '-';
        } else if ($value < 5) {
            return 'Очень низкое';
        } else if ($value >= 5 && $value < 10) {
            return 'Низкое';
        } else if ($value >= 10 && $value < 15) {
            return 'Среднее';
        } else {
            return 'Высокое';
        }
    } 

    public static function graduateAbsorbedSum($value) {
        if ($value < 0) {
            return '-';
        } else if ($value >= 0 && $value < 5.1) {
            return 'Очень низкое';
        } else if ($value >= 5.1 && $value < 10.1) {
            return 'Низкое';
        } else if ($value >= 10.1 && $value < 15.1) {
            return 'Среднее';
        } else if ($value >= 15.1 && $value < 20.1) {
            return 'Повышенное';
        } else if ($value >= 20.1 && $value < 30.1) {
            return 'Высокое';
        } else {
            return 'Очень высокое';
        }
    }

    public static function graduateCalcium($value) {
        if ($value < 0) {
            return '-';
        } else if ($value >= 0 && $value < 2.6) {
            return 'Очень низкое';
        } else if ($value >= 2.6 && $value < 5.1) {
            return 'Низкое';
        } else if ($value >= 5.1 && $value < 10.1) {
            return 'Среднее';
        } else if ($value >= 10.1 && $value < 15.1) {
            return 'Повышенное';
        } else if ($value >= 15.1 && $value < 20.0) {
            return 'Высокое';
        } else {
            return 'Очень высокое';
        }
    }

    public static function graduateMagnesium($value) {
        if ($value < 0) {
            return '-';
        } else if ($value >= 0 && $value < 0.6) {
            return 'Очень низкое';
        } else if ($value >= 0.6 && $value < 1.1) {
            return 'Низкое';
        } else if ($value >= 1.1 && $value < 2.1) {
            return 'Среднее';
        } else if ($value >= 2.1 && $value < 3.1) {
            return 'Повышенное';
        } else if ($value >= 3.1 && $value < 4.0) {
            return 'Высокое';
        } else {
            return 'Очень высокое';
        }
    }

    public static function graduateMn($value) {
        if ($value < 0) {
            return '-';
        } else if ($value < 10.0) {
            return 'Низкое';
        } else if ($value >= 10.0 && $value < 20.0) {
            return 'Среднее';
        } else {
            return 'Высокое';
        }
    }

    public static function graduateZn($value) {
        if ($value < 0) {
            return '-';
        } else if ($value < 2.1) {
            return 'Низкое';
        } else if ($value >= 2.1 && $value < 5.0) {
            return 'Среднее';
        } else {
            return 'Высокое';
        }
    }

    public static function graduateCu($value) {
        if ($value < 0) {
            return '-';
        } else if ($value < 0.21) {
            return 'Низкое';
        } else if ($value >= 0.21 && $value < 0.5) {
            return 'Среднее';
        } else {
            return 'Высокое';
        }
    }

    public static function graduateSalinity($value) {
        if ($value < 0) {
            return '-';
        } else if ($value >= 0 && $value < 2) {
            return 'Незасоленное';
        } else if ($value >= 2 && $value < 4) {
            return 'Слабо соленое';
        } else if ($value >= 4 && $value < 8) {
            return 'Умеренно соленое';
        } else if ($value >= 8 && $value < 16) {
            return 'Сильно соленое';
        } else {
            return 'Очень сильно соленое';
        }
    }
}
