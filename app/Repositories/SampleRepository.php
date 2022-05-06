<?php

namespace App\Repositories;

use App\Models\Sample;
use App\Repositories\BaseRepository;

/**
 * Class SampleRepository
 * @package App\Repositories
 * @version October 27, 2021, 6:23 am UTC
*/

class SampleRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'point_id',
        'date_selected',
        'date_received',
        'quantity',
        'passed',
        'accepted',
        'notes'
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
        return Sample::class;
    }
}
