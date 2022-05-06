<?php

namespace App\Repositories;

use App\Models\Result;
use App\Repositories\BaseRepository;

/**
 * Class ResultRepository
 * @package App\Repositories
 * @version October 27, 2021, 6:30 am UTC
*/

class ResultRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'field_id',
        'passed',
        'accepted',
        'value1',
        'value2',
        'value3',
        'value4',
        'value5',
        'value6',
        'value7',
        'value8',
        'value9',
        'value10',
        'value11',
        'value12',
        'value13'
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
        return Result::class;
    }
}
