<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Models;


use App\Exceptions\AppException;
use App\Models\Base\BaseModel;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AssetLionParcel extends MasterModel
{
    protected $table = 'asset_lion_parcels';

    protected $defaultColumn = [
        'id',
        'area_code',
        'city',
        'cost_route',
        'booking_route',
        'is_city',
    ];

    public static function rules($id = null)
    {
        return [
            'area_code' => 'string',
            'city' => 'string',
            'cost_route' => 'string',
            'booking_route' => 'string',
            'is_city' => 'boolean'
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

        $model->area_code = $req->get('area_code');
        $model->city = $req->get('city');
        $model->cost_route = $req->get('cost_route');
        $model->booking_route = strtolower($req->get('booking_route'));
        $model->is_city = $req->get('is_city');

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
            $data = $data->where("city", "LIKE", "%" . strtolower($query) . "%")
                ->orWhereRaw("CAST(id AS TEXT) LIKE '%$query%'");
        }

        return $data;
    }

    /**
     * @param $areaId
     * @param $type
     * @return string
     * @throws \Exception
     */
    public function costsRoute($areaId, $type)
    {
        try {
            if (empty($type) || empty($areaId))
                throw AppException::inst(
                    Response::HTTP_BAD_REQUEST,
                    "Route request does not exist."
                );

            $joinTable = "asset_regions";

            if ($type == "city")
                $joinTable = "asset_districts";

            $result = DB::table("asset_lion_parcels As lp")
                ->select("lp.cost_route")
                ->leftJoin($joinTable . " as join",
                    "join.lion_parcel_id",
                    "=",
                    "lp.id")
                ->where("join.id", "=", $areaId)
                ->first();

            $h = empty($result) ? "" : $result->cost_route;

            return $h;
        } catch (\Exception $e) {
            throw $e;
        }

    }
}