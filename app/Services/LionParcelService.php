<?php
/**
 * @author Jehan Afwazi Ahmad <jehan.afwazi@gmail.com>.
 */


namespace App\Services;


use App\Cores\Jsonable;
use App\Cores\ZHttpClient;
use App\Services\contracts\LionParcelServiceContract;
use Illuminate\Http\Request;
use GuzzleHttp\Promise;

class LionParcelService implements LionParcelServiceContract
{

    use Jsonable;

    private $client;

    public function __construct()
    {
        $this->client = ZHttpClient::init(config("gateway.connection.lion_parcel.api_url"));
    }

    /**
     * @param array $request
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    public function shippingCost(array $request)
    {
        try {

            if (empty($request['origin'])
                || empty($request['destination'])
                || empty($request['weight'])) {
                return [];
            }

            $signature = "H7KoLR7PTz94uS9/pKuJIJ6PbJM=";

            $promise = [
                'result' => $this->client->requestAsync(
                    'POST',
                    $this->client->url("/eLexysTariffService.svc/CalculateTariff?cf_signature=" . $signature),
                    array_merge(
                        ['headers' => ['Content-Type' => 'application/json']],
                        ['json' => [
                            'Origin' => $request['origin'],
                            'Destination' => $request['destination'],
                            'Weight' => $request['weight'] / 1000 //request in gram
                        ]]
                    ))];

            $resultUnwrap = Promise\unwrap($promise);

            $data = json_decode($this->safeDecode($resultUnwrap['result']->getBody()));

            if (collect($data->ErrorStatus)
                    ->filter(function ($o) {
                        return $o->ErrorMessage == "OK";
                    })->count() == 0) {
                return []; // just empty when error
            }

            return $this->shippingCostTransform($data->TariffResult);

        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * @param array $request
     * @return mixed
     * @throws \Exception
     * @throws \Throwable
     */
    public function trackShipment(array $request)
    {
        try {

            $signature = "TeUnxzqUs8DkWNjPo4WtGI5jxU=";

            $promise = [
                'result' => $this->client->requestAsync(
                    'POST',
                    $this->client->url("/eLexysTrackingService.svc/GetTracking?cf_signature=" . $signature),
                    array_merge(
                        ['headers' => ['Content-Type' => 'application/json']],
                        ['json' => ['SttNumber' => $request["sttNumber"]]]
                    ))];

            $resultUnwrap = Promise\unwrap($promise);

            $data = json_decode($this->safeDecode($resultUnwrap['result']->getBody()));

            return $data;

        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function shippingCostTransform(array $data)
    {

        $getServiceFn = function ($service, $type) {
            switch (true) {
                case $service->Product === "REGPACK":

                    switch (true) {
                        case $type === "desc":
                            return "Regular Package Services";
                            break;
                        case $type === "etd":
                            return "1 - 3 Hari Kerja.";
                            break;
                        default:
                            return "";
                    }

                    break;

                case $service->Product === "ONEPACK":

                    switch (true) {
                        case $type === "desc":
                            return "Overnight Express Package Services";
                            break;
                        case $type === "etd":
                            return "1 - 2 Hari Kerja.";
                            break;
                        default:
                            return "";
                    }

                    break;

                case $service->Product === "CI-PACK":

                    switch (true) {
                        case $type === "desc":
                            return "City Package / City Courier";
                            break;
                        case $type === "etd":
                            return "1 - 2 Hari Kerja.";
                            break;
                        default:
                            return "";
                    }

                    break;

                default:
                    return "";
            }
        };

        return array_map(function ($o) use ($getServiceFn) {
            return [
                "code" => "lp",
                "name" => "Lion Parcel",
                "service" => $o->Product . " - $o->ServiceType",
                "description" => $getServiceFn($o, "desc"),
                "value" => $o->TotalAmountTariff,
                "etd" => $getServiceFn($o, "etd"),
                "note" => "PublishRate $o->PublishRate"
            ];
        }, $data);
    }
}