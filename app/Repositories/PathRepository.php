<?php

namespace App\Repositories;

use App\Models\Path;
use App\Repositories\BaseRepository;

/**
 * Class PathRepository
 * @package App\Repositories
 * @version December 23, 2021, 3:34 pm +06
*/

class PathRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'date_started',
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
        return Path::class;
    }
}
