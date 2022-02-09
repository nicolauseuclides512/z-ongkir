<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Models\AssetDistrict;
use App\Models\AssetRegion;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class AssetRegionController extends BaseController
{

    public $name = 'Asset Region';

    public $sortBy = array('id', 'name', 'created_at', 'updated_at');

    protected $select = ['id', 'name', 'district_id'];

    public function __construct(Request $request)
    {
        parent::__construct(AssetRegion::inst(), $request);
    }

    public function getByDistrict($id)
    {
        return $this->json(
            Response::HTTP_ACCEPTED,
            'regions fetched.',
            $this->model->getByDistrict($id));
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    //TODO(ekli): yang hardcode perlu dioptimasi
    public function searchCities()
    {
        $key = $this->request->get('q');
        $type = $this->request->get('type');

        $district = collect([]);
        $region = collect([]);

        switch (strtolower($type)) {
            case 'city':
                $district = AssetDistrict::where('name', 'LIKE', strtolower("$key%"))
                    ->get(['id', 'name', 'type', 'province_id']);
                break;
            case 'subdistrict':
                $region = AssetRegion::whereHas('district',
                    function ($q) use ($key) {
                        return $q->where('name', 'LIKE', strtolower("$key%"));
                    })
                    ->orWhere('name', 'LIKE', strtolower("$key%"))
                    ->get(['id', 'name', 'type', 'district_id']);
                break;
            default :
                $region = AssetRegion::whereHas('district',
                    function ($q) use ($key) {
                        return $q->where('name', 'LIKE', strtolower("$key%"));
                    })
                    ->orWhere('name', 'LIKE', strtolower("$key%"))
                    ->get(['id', 'name', 'type', 'district_id']);

                $district = AssetDistrict::where('name', 'LIKE', strtolower("$key%"))
                    ->get(['id', 'name', 'type', 'province_id']);
        };

        $result = array_merge($district->toArray(), $region->toArray());

        foreach ($result as $subkey => &$city) {
            if ($city['id'] > 151 && $city['id'] <= 155) {
                unset($result[$subkey]);
            }
            if ($city['id'] === 151) {
                $city['name'] = str_replace(' Barat', '', $city['name']);
                $city['full_name'] = str_replace(' Barat', '', $city['full_name']);
            }
        }
        $result = array_values($result);

        return $this->json(Response::HTTP_ACCEPTED, 'result city by ' . $key, $result);

    }

    protected function list()
    {
        try {
            if ($this->request->get('type') == 'file') {
                $countryJson = Storage::disk('local')
                    ->get('json/regions.json');

                return $this->json(
                    Response::HTTP_OK,
                    "Fetch $this->name",
                    json_decode($countryJson, true)
                );
            }
            $list = $this->model
                ->where('status', true)
                ->select($this->select)
                ->get();

            return $this
                ->json(Response::HTTP_OK,
                    "Fetch $this->name",
                    $this->model->listAll()
                );

        } catch (\Exception $e) {
            return $this->jsonExceptions($e);
        }
    }
}
