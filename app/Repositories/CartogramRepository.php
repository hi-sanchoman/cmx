<?php

namespace App\Repositories;

use App\Models\Cartogram;
use App\Repositories\BaseRepository;

/**
 * Class CartogramRepository
 * @package App\Repositories
 * @version October 27, 2021, 6:41 am UTC
*/

class CartogramRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'field_id',
        'status',
        'access_url'
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
        return Cartogram::class;
    }
}
