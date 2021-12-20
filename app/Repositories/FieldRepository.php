<?php

namespace App\Repositories;

use App\Models\Field;
use App\Repositories\BaseRepository;

/**
 * Class FieldRepository
 * @package App\Repositories
 * @version October 27, 2021, 3:57 am UTC
*/

class FieldRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cadnum',
        'type',
        'square',
        'culture',
        'description',
        'region_id'
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
        return Field::class;
    }
}
