<?php

namespace App\Repositories;

use App\Models\Point;
use App\Repositories\BaseRepository;

/**
 * Class PointRepository
 * @package App\Repositories
 * @version October 27, 2021, 6:18 am UTC
*/

class PointRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'subpolygon_id',
        'lat',
        'lon'
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
        return Point::class;
    }
}
