<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Client
 * @package App\Models
 * @version October 27, 2021, 3:42 am UTC
 *
 * @property string $firstname
 * @property string $lastname
 * @property integer $region_id
 * @property string $iin
 * @property string $address
 */
class Client extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'clients';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'firstname',
        'lastname',
        'region_id',
        'iin',
        'address',
        'phone',
        'email',
        'num',
        'khname',
        'password',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'firstname' => 'string',
        'lastname' => 'string',
        'region_id' => 'integer',
        'iin' => 'string',
        'address' => 'string',
        'phone' => 'string',
        'email' => 'string',
        'num' => 'integer',
        'khname' => 'string',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'firstname' => 'required',
        'lastname' => 'required',
        'region_id' => 'required',
        'iin' => 'required',
        'num' => 'required',
        'khname' => 'required',
    ];

    public function region() {
        return $this->belongsTo(Region::class);
    }

    public function fields() {
        return $this->hasMany(Field::class);
    }



    public static function dropdown() {
        $res = [];

        $clients = self::get();

        foreach ($clients as $key => $client) {
            $res[$client->id] = $client->khname . ' (' . $client->lastname . ' ' . $client->firstname . ')';
        }

        return $res;
    }


    public static function fieldsDropdown(Client $client) {
        $res = [];

        $fields = $client->fields;

        foreach ($fields as $field) {
            $res[$field->id] = 'Поле №' . $field->num . ' (' . $field->cadnum . ')';
        }

        return $res;
    }
}
