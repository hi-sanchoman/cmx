<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Cartogram
 * @package App\Models
 * @version October 27, 2021, 6:41 am UTC
 *
 * @property integer $field_id
 * @property string $status
 * @property string $path
 * @property string $access_url
 */
class Cartogram extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'cartograms';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'field_id',
        'status',
        'path',
        'access_url'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'field_id' => 'integer',
        'status' => 'string',
        'path' => 'string',
        'access_url' => 'string'
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'field_id' => 'required',
        'status' => 'required',
        'path' => 'required',
    ];

    
    public function field() {
        return $this->belongsTo(Field::class);
    }




    public static function getGraduate($value, $options = []) {
        if ($value == 'humus') {
            return [
                '<2' => [
                    'plan' => '< 2.0',
                    'color' => '#F0F8FF',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень низкое',
                ],
                '2-4' => [
                    'plan' => '2.1-4.0',
                    'color' => '#89CFF0',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкое',
                ],
                '4-6' => [
                    'plan' => '4.1-6.0',
                    'color' => '#318CE7',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднее',
                ],
                '6-8' => [
                    'plan' => '6.1-8.0',
                    'color' => '#0039a6',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Повышенное',
                ],
                '8-10' => [
                    'plan' => '8.1-10.0',
                    'color' => '#034694',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокое',
                ],
                '>10' => [
                    'plan' => '> 10.0',
                    'color' => '#002D62',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень высокое',
                ],
            ];
        } else if ($value == 'ph') {
            return [
                '<4.5' => [
                    'plan' => '< 4.6',
                    'color' => '#b00000',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Сильнокислая',
                ],
                '4.6-5' => [
                    'plan' => '4.6-5.0',
                    'color' => '#c41e3a',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднекислая',
                ],
                '5.1-5.5' => [
                    'plan' => '5.1-5.5',
                    'color' => '#ff2400',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Слабокислая',
                ],
                '5.6-6' => [
                    'plan' => '5.6-6.0',
                    'color' => '#DC143C',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Близкая к нейтр.',
                ],
                '6.1-7' => [
                    'plan' => '6.1-7.0',
                    'color' => '#ffe5b4',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Нейтральная',
                ],
                '7.1-8' => [
                    'plan' => '7.1-8.0',
                    'color' => '#8b00ff',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Слабощелочная',
                ],
                '>8' => [
                    'plan' => '> 8.0',
                    'color' => '#00bfff',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Щелочная',
                ],
            ];
        } else if ($value == 'no3_2') {
            return [
                '<5' => [
                    'plan' => '<5',
                    'color' => '#F0E68C',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень низкое',
                ],
                '5-10' => [
                    'plan' => '5-10',
                    'color' => '#FFC72C',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкая',
                ],
                '10-15' => [
                    'plan' => '10-15',
                    'color' => '#FFAF4D',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Средняя',
                ],
                '>15' => [
                    'plan' => '>15',
                    'color' => '#FF8C00',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокая',
                ],
            ];
        } else if ($value == 'no3') {
            return [
                '<10' => [
                    'plan' => '<10',
                    'color' => '#F0E68C',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень низкое',
                ],
                '10-15' => [
                    'plan' => '10-15',
                    'color' => '#FFC72C',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкая',
                ],
                '15-20' => [
                    'plan' => '15-20',
                    'color' => '#FFAF4D',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Средняя',
                ],
                '>20' => [
                    'plan' => '>20',
                    'color' => '#FF8C00',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокая',
                ],
            ];
        } else if ($value == 'p') {
            return [
                '<10' => [
                    'plan' => '< 10',
                    'color' => '#30D5C8',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень низкое',
                ],
                '10-15' => [
                    'plan' => '11-15',
                    'color' => '#ADD8E6',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкое',
                ],
                '16-30' => [
                    'plan' => '16-30',
                    'color' => '#80A6FF',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднее',
                ],
                '31-45' => [
                    'plan' => '31-45',
                    'color' => '#4169E1',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Повышенное',
                ],
                '45-60' => [
                    'plan' => '45-60',
                    'color' => '#0000ff',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокое',
                ],
                '>60' => [
                    'plan' => '> 60',
                    'color' => '#00008b',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень высокое',
                ],
            ];
        } else if ($value == 'k') {
            return [
                '<101' => [
                    'plan' => '< 100',
                    'color' => '#ffff00',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень низкое',
                ],
                '101-201' => [
                    'plan' => '101-200',
                    'color' => '#ffc93b',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкое',
                ],
                '201-301' => [
                    'plan' => '201-300',
                    'color' => '#ffa500',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднее',
                ],
                '301-401' => [
                    'plan' => '301-400',
                    'color' => '#cd853f',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Повышенное',
                ],
                '401-601' => [
                    'plan' => '401-600',
                    'color' => '#964b00',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокое',
                ],
                '>601' => [
                    'plan' => '> 600',
                    'color' => '#654321',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень высокое',
                ],
            ];
        } else if ($value == 's') {
            return [
                '<6' => [
                    'plan' => '< 6.0',
                    'color' => '#ffff00',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкое',
                ],
                '6-12' => [
                    'plan' => '6.0-12.0',
                    'color' => '#9b870c',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднее',
                ],
                '>12' => [
                    'plan' => '> 12.0',
                    'color' => '#ffa500',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокое',
                ],
            ];
        } else if ($value == 'absorbed_sum') {
            return [
                '<5' => [
                    'plan' => '< 5.0',
                    'color' => '#DCDCDC',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень низкое',
                ],
                '5-10' => [
                    'plan' => '5.1-10.0',
                    'color' => '#A9A9A9',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкое',
                ],
                '10-15' => [
                    'plan' => '10.1-15.0',
                    'color' => '#696969',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднее',
                ],
                '15-20' => [
                    'plan' => '15.1-20.1',
                    'color' => '#CCCC00',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Повышенное',
                ],
                '20-30' => [
                    'plan' => '20.1-30.1',
                    'color' => '#D2B48C',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокое',
                ],
                '>30' => [
                    'plan' => '> 30',
                    'color' => '#8B4513',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень высокое',
                ],
            ];
        } else if ($value == 'calcium') {
            return [
                '<2.5' => [
                    'plan' => '< 2.5',
                    'color' => '#B0E0E6',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень низкое',
                ],
                '2.5-5' => [
                    'plan' => '2.6-5',
                    'color' => '#87CEFA',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкое',
                ],
                '5-10' => [
                    'plan' => '5.1-10.0',
                    'color' => '#98FB98',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднее',
                ],
                '10-15' => [
                    'plan' => '10.0-15.0',
                    'color' => '#ADFF2F',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Повышенное',
                ],
                '15-20' => [
                    'plan' => '15.1-20.0',
                    'color' => '#4169E1',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокое',
                ],
                '>20' => [
                    'plan' => '> 20.0',
                    'color' => '#556B2F',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень высокое',
                ],
            ];
        } else if ($value == 'magnesium') {
            return [
                '<0.5' => [
                    'plan' => '< 0.5',
                    'color' => '#B0E0E6',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень низкое',
                ],
                '0.5-1' => [
                    'plan' => '0.6-1.0',
                    'color' => '#87CEFA',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкое',
                ],
                '1-2' => [
                    'plan' => '1.1-2.0',
                    'color' => '#98FB98',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднее',
                ],
                '2-3' => [
                    'plan' => '2.1-3.0',
                    'color' => '#ADFF2F',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Повышенное',
                ],
                '3-4' => [
                    'plan' => '3.1-4.0',
                    'color' => '#4169E1',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Очень высокое',
                ],
                '>4' => [
                    'plan' => '> 4.0',
                    'color' => '#556B2F',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокое',
                ],
            ];
        } else if ($value == 'mn') {
            return [
                '<10' => [
                    'plan' => '< 10',
                    'color' => '#С0С0С0',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкое',
                ],
                '10-20' => [
                    'plan' => '10.0-20.0',
                    'color' => '#808080',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднее',
                ],
                '>20' => [
                    'plan' => '> 20.0',
                    'color' => '#363636',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокое',
                ],
            ];
        } else if ($value == 'zn') {
            return [
                '<2' => [
                    'plan' => '< 2.0',
                    'color' => '#FFC0CB',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкое',
                ],
                '2-5' => [
                    'plan' => '2.1-5.0',
                    'color' => '#FF69B4',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднее',
                ],
                '>5' => [
                    'plan' => '> 5.0',
                    'color' => '#DB7093',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокое',
                ],
            ];
        } else if ($value == 'cu') {
            return [
                '<0.2' => [
                    'plan' => '< 0.20',
                    'color' => '#FFFF99',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Низкое',
                ],
                '0.2-0.5' => [
                    'plan' => '0.21-0.5',
                    'color' => '#FFFF33',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Среднее',
                ],
                '>0.5' => [
                    'plan' => '> 0.50',
                    'color' => '#999900',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Высокое',
                ],
            ];
        } else if ($value == 'salinity') {
            return [
                '<2' => [
                    'plan' => '< 2',
                    'color' => '#98FB98',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Незасоленное',
                ],
                '2-4' => [
                    'plan' => '2-4',
                    'color' => '#32CD32',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Слабо соленое',
                ],
                '4-8' => [
                    'plan' => '4-8',
                    'color' => '#00FF00',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Умеренно соленое',
                ],
                '8-16' => [
                    'plan' => '8-16',
                    'color' => '#228B22',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Сильно соленое',
                ],
                '>16' => [
                    'plan' => '>16',
                    'color' => '#696969',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Оч. сильно сол.',
                ],
            ];
        } else if ($value == 'salinity_2') {
            return [
                '<4' => [
                    'plan' => '< 4',
                    'color' => '#98FB98',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Незасоленное',
                ],
                '4-8' => [
                    'plan' => '4-8',
                    'color' => '#32CD32',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Слабо соленое',
                ],
                '8-16' => [
                    'plan' => '8-16',
                    'color' => '#00FF00',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Умеренно соленое',
                ],
                '16-24' => [
                    'plan' => '16-24',
                    'color' => '#228B22',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Сильно соленое',
                ],
                '>24' => [
                    'plan' => '>24',
                    'color' => '#696969',
                    'height' => 0,
                    'subtotal' => 0,
                    'caption' => 'Оч. сильно сол.',
                ],
            ];
        }



    }
}


