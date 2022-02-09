<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Models;


use App\Models\Base\BaseModel;
use Illuminate\Support\Collection;

class AssetProvince extends MasterModel
{
    protected $table = 'asset_provinces';

    protected $filterNameCfg = 'asset_province';

//    protected $with = ['districts'];

    protected $casts = [
        'id' => 'integer',
        'country_id' => 'integer',
        'name' => 'string'
    ];

    public function districts()
    {
        return $this->hasMany(AssetDistrict::class, 'province_id');
    }

    public function country()
    {
        return $this->belongsTo(AssetCountry::class, 'country_id');
    }

    public static function rules($id = null)
    {
        $uniqueQueryHandler = $id ? ',' . $id . ',id' : '';

        return [
            'country_id' => 'required|integer|exists:asset_countries,id',
            'name' => 'required|string|max:100|unique:asset_provinces,name' . $uniqueQueryHandler,
            'status' => 'boolean'
        ];
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

        $model->name = $req->get('name');
        $model->country_id = $req->get('country_id');
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
                ->where("name", "ILIKE", "%" . $query . "%")
                ->orWhereRaw("CAST(id AS TEXT) ILIKE '%$query%'");

        }

        return $data;
    }

    public function getByCountry($id)
    {
        return (!is_null($id)) ? $this->where('country_id', $id)->get() : null;
    }
}