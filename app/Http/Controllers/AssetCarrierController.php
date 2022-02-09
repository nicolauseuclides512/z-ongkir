<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Models\AssetCarrier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * class AssetCarrierController
 */
class AssetCarrierController extends BaseController
{

    public $name = 'Asset Carrier';

    public $statusColumn = 'status';

    public $sortBy = ['id', 'name', 'created_at', 'updated_at'];

    protected $select = ['id', 'name', 'code', 'logo'];

    public function __construct(Request $request)
    {
        parent::__construct(AssetCarrier::inst(), $request);
    }

    private function _filterCarriersList(array &$data)
    {
        foreach ($data as $key => &$carriers) {
            if (strpos($carriers['code'], 'jne') === false && strpos($carriers['code'], 'pos') === false
                && strpos($carriers['code'], 'tiki') === false && strpos($carriers['code'], 'wahana') === false
                && strpos($carriers['code'], 'jnt') === false && strpos($carriers['code'], 'sicepat') === false) {
                unset($data[$key]);
            }
        }
        return $data;
    }

    protected function list()
    {
        $result = $this->model
            ->where('status', true)
            ->select($this->select)->get()
            ->toArray();

        $this->_filterCarriersList($result);
        $result = array_values($result);
        foreach ($result as &$data){
            $temp = substr($data['logo'],0,67);
//            var_dump($temp);
            $data['image'] = ['small' => $temp.$data['code'].'_id_small.png',
                'medium' => $temp.$data['code'].'_id.png',
                'big' => $temp.$data['code'].'_id_big.png'];

        }

        return $this
            ->json(Response::HTTP_OK,
                "Fetch $this->name",
                $result
            );
    }
}
