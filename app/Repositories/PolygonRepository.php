<?php

namespace App\Repositories;

use App\Models\Polygon;
use App\Repositories\BaseRepository;

/**
 * Class PolygonRepository
 * @package App\Repositories
 * @version October 27, 2021, 6:15 am UTC
*/

class PolygonRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'field_id',
        'geometry'
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
        return Polygon::class;
    }
}
