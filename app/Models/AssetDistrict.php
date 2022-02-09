<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Models;


use App\Models\Base\BaseModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AssetDistrict extends MasterModel
{
    protected $table = 'asset_districts';

    protected $filterNameCfg = 'asset_district';

//    protected $with = ['regions'];

    protected $appends = ['full_name', 'priority_region'];

    public function regions()
    {
        return $this->hasMany(AssetRegion::class, 'district_id');
    }

    public function province()
    {
        return $this->belongsTo(AssetProvince::class, 'province_id');
    }

    public function getFullNameAttribute()
    {
        $province = $this->province()->first();
        return ($province) ? $this->name . ', ' . $province->name : '';
    }

    public function getPriorityRegionAttribute()
    {
        return $this->regions()
            ->where('is_priority', true)
            ->select('id', 'name', 'district_id')->first();
    }

    public static function rules($id = null)
    {
        return [
            'province_id' => 'required|integer|exists:asset_provinces,id',
            'name' => 'required|string|max:100',
            'zip' => 'string',
            'type' => 'string|in:city',
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

        $model->province_id = $req->get('province_id');
        $model->name = $req->get('name');
        $model->type = strtolower($req->get('type'));
        $model->zip = $req->get('zip');
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
                ->where("name", "LIKE", "%" . $query . "%")
                ->orWhereRaw("CAST(id AS TEXT) LIKE '%$query%'");
        }

        return $data;
    }

    public function getByProvince($id)
    {
        return (!is_null($id)) ? $this->where('province_id', $id)->get() : null;
    }

    public function listAll($select = [], $status = 1)
    {
        $districtResult = DB::table($this->table . ' AS ad')
            ->join(
                "asset_provinces",
                "province_id",
                "=",
                "asset_provinces.id")
            ->leftJoin(
                DB::raw("(SELECT ar.id, ar.name, ar.district_id FROM asset_regions ar WHERE is_priority=1) priority_region"),
                function ($join) {
                    $join->on('ad.id', '=', 'priority_region.district_id');
                }
            )
            ->select(
                "ad.id",
                "ad.name",
                DB::raw("CONCAT(ad.name,', ',asset_provinces.name) AS full_name"),
//                DB::raw("(SELECT ar.id FROM asset_regions ar WHERE ar.district_id=ad.id AND is_priority=1 limit 1) as priority_region_id"),
//                DB::raw("(SELECT ar.name FROM asset_regions ar WHERE ar.district_id=ad.id AND is_priority=1 limit 1) as priority_region_name")
                "priority_region.id as priority_region_id",
                "priority_region.name as priority_region_name"
            )
            ->where('ad.status', $status)
            ->get();

        return $districtResult->map(function ($obj) {
            $obj->priority_region = (object)[
                "id" => $obj->priority_region_id,
                "name" => $obj->priority_region_name,
                "full_name" => $obj->name . ", " . $obj->priority_region_name
            ];

            unset($obj->priority_region_id);
            unset($obj->priority_region_name);

            return $obj;
        });

    }
}