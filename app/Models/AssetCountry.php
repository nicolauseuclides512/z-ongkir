<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Models;


use App\Models\Base\BaseModel;
use Illuminate\Support\Collection;

class AssetCountry extends MasterModel
{
    protected $table = 'asset_countries';

    protected $filterNameCfg = 'asset_country';

    public function provinces()
    {
        return $this->hasMany(AssetProvince::class, 'country_id');
    }

    public static function rules($id = null)
    {
        $uniqueQueryHandler = $id ? ',' . $id . ',id' : '';

        return array(
            'code' => 'nullable|string|max:5|unique:asset_countries,code' . $uniqueQueryHandler,
            'name' => 'required|string|max:100',
            'status' => 'boolean'
        );
    }

    public static function inst()
    {
        return new self();
    }

    public function populate($request, BaseModel $model = null)
    {
        $req = new Collection($request);

        if (is_null($model)) {
            $model = self::inst();
        }

        $model->code = $req->get('code');
        $model->name = $req->get('name');
        $model->status = $req->get('status');

        return $model;
    }

    public function scopeFilter($q, $filterBy = "", $query = "")
    {
        $data = $q;

        switch ($filterBy) {
            case self::STATUS_INACTIVE:
                $data = $data->where("status", false);
                break;
            case self::STATUS_ACTIVE:
                $data = $data->where("status", true);
                break;
        }

        if (!empty($query)) {
            $data = $data
                ->where("code", "ILIKE", "%" . $query . "%")
                ->orWhereRaw("CAST(id AS TEXT) ILIKE '%$query%'")
                ->orWhere("name", "ILIKE", "%" . $query . "%");
        }

        return $data;
    }
}