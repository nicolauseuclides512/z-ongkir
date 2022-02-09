<?php

namespace App\Http\Controllers;

/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

use App\Cores\ZHttpClient;
use App\Exceptions\AppException;
use App\Http\Controllers\Base\BaseController;
use App\Http\Requests\RajaOngkirCostRequest;
use App\Http\Requests\RajaOngkirWaybillRequest;
use App\Http\Requests\ShippingCostRequest;
use App\Http\Requests\TrackShipmentRequest;
use App\Models\AssetDistrict;
use App\Models\AssetLionParcel;
use App\Services\contracts\LionParcelServiceContract;
use App\Services\contracts\RajaOngkirServiceContract;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;


class BrokerController extends BaseController
{

    private $lpService;
    private $roService;

    public function __construct(
        RajaOngkirServiceContract $roService,
        LionParcelServiceContract $lpService
    )
    {
        parent::__construct();
        $this->lpService = $lpService;
        $this->roService = $roService;
    }


    public function shippingCosts(ShippingCostRequest $request)
    {
        try {

            $result = $this->roService->shippingCosts($request->all());

            if (strpos($request['courier'], 'lp')) {
                $lpResult = $this->lpService->shippingCost([
                    "origin" => AssetLionParcel::inst()
                        ->costsRoute(
                            $request->get("origin"),
                            $request->get('originType')
                        ),
                    "destination" => AssetLionParcel::inst()
                        ->costsRoute(
                            $request->get("destination"),
                            $request->get('destinationType')
                        ),
                    "weight" => $request->get("weight")
                ]);

                $result['results'] = array_merge($result['results'], $lpResult);
            }

            $result['query']['courier'] = $request->get("courier");

            return $this->json(
                Response::HTTP_OK,
                'Get Shipping Cost Done.',
                $result);

        } catch (\Exception $e) {
            return $this->jsonExceptions($e);
        }
    }

    public function trackShipments(TrackShipmentRequest $request)
    {
        try {

            $data = $this->roService->trackShipments($request->all());

            return $this->json(
                Response::HTTP_OK,
                'Track Shipment Done.',
                $data);

        } catch (\Exception $e) {
            return $this->jsonExceptions($e);
        }
    }
}