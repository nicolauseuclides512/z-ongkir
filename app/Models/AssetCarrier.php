<?php

namespace App\Models;

use App\Models\Base\BaseModel;
use Illuminate\Support\Collection;

class AssetCarrier extends MasterModel
{
    protected $table = 'asset_carriers';

    protected $columnStatus = 'status';

    protected $columnDefault = ["*"];

    protected $columnSimple = ["*"];

    protected $showActiveOnly = false;

    public static function rules($id = null)
    {
        return [
            'name' => 'required|string|max:100',
            'code' => 'string',
            'logo' => 'string',
            'status' => 'boolean'
        ];
    }

    public function populate($request = [], BaseModel $model = null)
    {

        if (is_null($model))
            $model = self::inst();

        $req = new Collection($request);
        $model->name = $req->get("name");
        $model->logo = $req->get("logo");
        $model->code = $req->get("code");
        $model->status = $req->get("status");

        return $model;
    }

    public static function inst()
    {
        return new self();
    }

    public function scopeFilter($q, $filterBy = "", $query = "")
    {
        $data = $q;

        switch ($filterBy) {
            case self::STATUS_ACTIVE :
                $data->where('status', "=", self::STATUS_ACTIVE);
                break;
            case self::STATUS_INACTIVE :
                $data->where('status', "=", self::STATUS_INACTIVE);
                break;
        }

        if (!empty($query)) {
            $data = $data
                ->where("name", "ILIKE", "%" . $query . "%");
        }

        return $data;
    }

}