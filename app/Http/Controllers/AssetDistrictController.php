<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Base\BaseController;
use App\Models\AssetDistrict;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class AssetDistrictController extends BaseController
{

    public $name = 'Asset District';

    public $sortBy = array('id', 'name', 'created_at', 'updated_at');

    protected $select = ['id', 'name', 'province_id'];

    public function __construct(Request $request)
    {
        parent::__construct(AssetDistrict::inst(), $request);
    }

    public function getByProvince($id)
    {
        return $this->json(Response::HTTP_ACCEPTED, 'districts fetched', $this->model->getByProvince($id));
    }

    //TODO(ekli): yang hardcode perlu dioptimasi
    protected function list()
    {
        try {

            if ($this->request->get('type') == 'file') {

                $countryJson = Storage::disk('local')
                    ->get('json/districts.json');

                return $this->json(
                    Response::HTTP_OK,
                    "Fetch $this->name",
                    json_decode($countryJson, true)
                );
            }

            $data = $this->model->listAll();

            foreach ($data as $key => $cities) {
                $cities = (array)$cities;
                if ($cities['id'] > 151 && $cities['id'] <= 155) {
                    unset($data[$key]);
                }
                if ($cities['id'] === 151) {
                    $cities['name'] = trim($cities['name'], 'Barat');
                    $cities['name'] = trim($cities['name'], ' ');
                }
            }

            $data = collect($data)->values()->all();

            return $this->json(
                Response::HTTP_OK,
                "Fetch $this->name",
                $data
            );

        } catch (\Exception $e) {
            return $this->jsonExceptions($e);
        }
    }
}
