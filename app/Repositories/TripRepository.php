<?php

namespace App\Repositories;

use App\Models\Trip;
use App\Repositories\BaseRepository;

/**
 * Class TripRepository
 * @package App\Repositories
 * @version October 27, 2021, 4:15 am UTC
*/

class TripRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date',
        'status',
        'field_id',
        'date_completed'
    ];

    /**
     * Return searchable fields
     *
     * @return array
     */
    public function getFieldsSearchable()
    {
        return $this->fieldSearchable;
    }

    /**
     * Configure the Model
     **/
    public function model()
    {
        return Trip::class;
    }
}
