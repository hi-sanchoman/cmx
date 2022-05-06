<?php

namespace App\Models;

use Eloquent as Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Protocol
 * @package App\Models
 * @version October 27, 2021, 6:44 am UTC
 *
 * @property integer $client_id
 * @property string $path
 * @property string $access_url
 */
class Protocol extends Model
{
    use SoftDeletes;

    use HasFactory;

    public $table = 'protocols';
    

    protected $dates = ['deleted_at'];



    public $fillable = [
        'client_id',
        'path',
        'access_url',
        'num',
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'client_id' => 'integer',
        'path' => 'string',
        'access_url' => 'string',
        'num' => 'integer',
    ];

    /**
     * Validation rules
     *
     * @var array
     */
    public static $rules = [
        'client_id' => 'required',
        'path' => 'required',
        'num' => 'required',
    ];

    
    public function client() {
        return $this->belongsTo(Client::class);
    }    
}
