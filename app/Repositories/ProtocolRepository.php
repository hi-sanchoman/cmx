<?php

namespace App\Repositories;

use App\Models\Protocol;
use App\Repositories\BaseRepository;

/**
 * Class ProtocolRepository
 * @package App\Repositories
 * @version October 27, 2021, 6:44 am UTC
*/

class ProtocolRepository extends BaseRepository
{
    /**
     * @var array
     */
    protected $fieldSearchable = [
        'cartogram_id',
        'path',
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
        return Protocol::class;
    }
}
