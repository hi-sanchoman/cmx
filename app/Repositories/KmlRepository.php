<?php

namespace App\Repositories;

use App\Models\Kml;
use App\Repositories\BaseRepository;

/**
 * Class KmlRepository
 * @package App\Repositories
 * @version October 27, 2021, 6:13 am UTC
*/

class KmlRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'path',
        'content',
        'field_id'
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
        return Kml::class;
    }
}
