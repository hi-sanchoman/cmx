<?php

namespace App\Repositories;

use App\Models\Subpolygon;
use App\Repositories\BaseRepository;

/**
 * Class SubpolygonRepository
 * @package App\Repositories
 * @version October 27, 2021, 6:17 am UTC
*/

class SubpolygonRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'polygon_id',
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
        return Subpolygon::class;
    }
}
