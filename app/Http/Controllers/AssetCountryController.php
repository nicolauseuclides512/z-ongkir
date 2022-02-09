<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Models\AssetCountry;
use App\Models\AssetDistrict;
use App\Models\AssetProvince;
use App\Models\AssetRegion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AssetCountryController extends BaseController
{
    public $name = 'Asset Country';

    public $statusColumn = 'status';

    public $sortBy = ['id', 'name', 'created_at', 'updated_at'];

    protected $select = ['id', 'name'];

    public function __construct(Request $request)
    {
        parent::__construct(AssetCountry::inst(), $request);
    }

    /**
     * get nested area with related data
     * @return \Illuminate\Http\JsonResponse
     */
    public function getNestedList()
    {
        return $this->json(Response::HTTP_OK, 'Fetch all carrier', $this->model->where('status', true)->with(
            ['provinces' => function ($q) {
                return $q->with(['districts' => function ($r) {
                    return $r->with('regions');
                }]);
            }]
        )->get());
    }

    /**
     * get area ignore relation
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAreaById()
    {
        try {
            $countryId = $this->request->get('countryId');
            $provinceId = $this->request->get('provinceId');
            $districtId = $this->request->get('districtId');
            $regionId = $this->request->get('regionId');

            Log::info('get area' . $countryId . $provinceId . $districtId . $regionId);

            return $this->json(Response::HTTP_OK, 'fetch area by id', [
                    'country' => !$countryId ? null : AssetCountry::where('id', $countryId)->select(['id', 'name'])->first(),
                    'province' => !$provinceId ? null : AssetProvince::where('id', $provinceId)->select(['id', 'name', 'country_id'])->first(),
                    'district' => !$districtId ? null : AssetDistrict::where('id', $districtId)->select(['id', 'name', 'province_id'])->first(),
                    'region' => !$regionId ? null : AssetRegion::where('id', $regionId)->select(['id', 'name', 'district_id'])->first()
                ]
            );

        } catch (\Exception $e) {
            return $this->jsonExceptions($e);
        }
    }

    protected function list()
    {
        try {
            if ($this->request->get('type') == 'file') {
                $countryJson = Storage::disk('local')
                    ->get('json/countries.json');

                return $this->json(
                    Response::HTTP_OK,
                    "Fetch $this->name",
                    json_decode($countryJson, true)
                );
            }

            return $this
                ->json(
                    Response::HTTP_OK,
                    "Fetch $this->name",
                    $this->model->listAll($this->select)
                );

        } catch (\Exception $e) {
            return $this->jsonExceptions($e);
        }
    }
}
