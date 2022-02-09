<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Models;


use App\Models\Base\BaseModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AssetRegion extends MasterModel
{
    protected $table = 'asset_regions';

    protected $appends = ['full_name'];

    protected $filterNameCfg = 'asset_region';

    protected $defaultColumn = [
        'id',
        'district_id',
        'name',
//        'status',
//        'zip',
//        'type',
    ];

    public function district()
    {
        return $this->belongsTo(AssetDistrict::class, 'district_id');
    }

    public function getFullNameAttribute()
    {
        $district = $this->district()->first();
        return ($district) ? $this->name . ', ' . $district->name : '';
    }

    public static function rules($id = null)
    {
        return [
            'district_id' => 'required|integer|exists:asset_districts,id',
            'name' => 'required|string|max:100',
            'status' => 'boolean',
            'zip' => 'nullable|string',
            'type' => 'nullable|string|in:subdistrict'
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
        $model->district_id = $req->get('district_id');
        $model->type = $req->get('type');
        $model->zip = strtolower($req->get('zip'));
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
            $data = $data->where("name", "LIKE", "%" . strtolower($query) . "%")
                ->orWhereRaw("CAST(id AS TEXT) LIKE '%$query%'");
        }

        return $data;
    }

    public function getByDistrict($id)
    {
        return (!is_null($id)) ? $this->where('district_id', $id)->get() : null;
    }

    public function listAll($select = [], $status = 1)
    {
        return DB::table($this->table)
            ->join(
                "asset_districts",
                "district_id",
                "=",
                "asset_districts.id")
            ->select(
                "asset_regions.id",
                "asset_regions.name",
                DB::raw("CONCAT(asset_regions.name,', ',asset_districts.name) AS full_name")
            )
            ->where('asset_regions.status', $status)
            ->get();
    }
}