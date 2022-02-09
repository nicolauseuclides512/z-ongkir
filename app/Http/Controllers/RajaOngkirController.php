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
use App\Models\AssetDistrict;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Promise;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;


/**
 * Class RajaOngkirController
 * @package App\Http\Controllers
 */
class RajaOngkirController extends BaseController
{

    /**
     * @var Client
     */
    private $client;

    /**
     * instant guzzle http client
     * RajaOngkirController constructor.
     */
    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => env('GATEWAY_RAJAONGKIR_DOMAIN'),
            'timeout' => config('gateway.timeout'),
            'connect_timeout' => config('gateway.connect_timeout')
        ]);

//        $this->client = $client->init(config("gateway.connection.raja_ongkir.api_url"));

    }

    /**
     * change etd attribute
     *
     * @param array $data
     * @return array
     */
    private function _transformEtdAttribute(array &$data)
    {
        foreach ($data as $key => &$kurir) {
            if (!empty($kurir['costs'])) {
                foreach ($kurir['costs'] as &$costs) {
                    if (!empty($costs['cost'])) {
                        foreach ($costs['cost'] as &$cost) {
                            if (empty($cost['value'])) {
                                unset($data[$key]);
                            }
                            if (strpos($cost['etd'], 'HARI') === false && strpos($cost['etd'], 'JAM') === false && strpos($cost['etd'], 'Hari') === false && strpos($cost['etd'], 'Jam') === false) {
                                $cost['etd'] = trim($cost['etd']) . ' HARI';
                            }
                        }
                    } else {
                        unset($data[$key]);
                    }
                }
            } else {
                unset($data[$key]);
            }
        }
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function _transformExpectedCourier(array &$data)
    {
        foreach ($data as $key => &$kurir) {
            if (strpos($kurir['code'], 'jne') === false && strpos($kurir['code'], 'pos') === false
                && strpos($kurir['code'], 'tiki') === false && strpos($kurir['code'], 'J&T') === false
                && strpos($kurir['code'], 'wahana') === false && strpos($kurir['code'], 'pandu') === false
                && strpos($kurir['code'], 'sicepat') === false) {
                unset($data[$key]);
            } else {
                foreach ($kurir['costs'] as $subkey => &$costs) {
                    if (strpos($costs['service'], 'ONS') === false && strpos($costs['service'], 'REG') === false
                        && strpos($costs['service'], 'Paket Kilat Khusus') === false && strpos($costs['service'], 'Express Next Day Barang') === false
                        && strpos($costs['service'], 'EZ') === false && strpos($costs['service'], 'OKE') === false
                        && strpos($costs['service'], 'YES') === false && strpos($costs['service'], 'DES') === false
                        && strpos($costs['service'], 'BEST') === false && strpos($costs['service'], 'CTC') === false) {
                        unset($kurir['costs'][$subkey]);
                    }
                    if (strpos($costs['service'], 'Paket Kilat Khusus') !== false) {
                        $costs['service'] = trim($costs['service'], 'Paket ');
                    }
                    if (strpos($costs['service'], 'Express Next Day Barang') !== false) {
                        $costs['service'] = trim($costs['service'], ' Next Day Barang');
                    }
                }
            }
        }
        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function _transformResult(array $data)
    {
        $output = [];
        foreach ($data as $kurir) {
            $main_fields = array('code' => $kurir['code'], 'name' => $kurir['name']);
            foreach ($kurir['costs'] as $costs) {
                $tCosts = $costs['cost'][0];
                unset($costs['cost']);
                $tmp = array_merge($main_fields, $costs, $tCosts);
                array_push($output, $tmp);
            }
        }
        return $output;
    }

    /**
     * reformat indexing in array inside
     *
     * @param array $data
     * @return array
     */
    private function _arrayValuesRecursive(array $data)
    {
        foreach ($data as $key => $kurir) {
            if (is_array($kurir)) {
                $data[$key] = $this->_arrayValuesRecursive($kurir);
            }
        }

        if (isset($data['costs'])) {
            $data['costs'] = array_values($data['costs']);
        }

        return $data;
    }

    /**
     * @unusdcode
     * @param RajaOngkirCostRequest $request
     * @return \Illuminate\Http\JsonResponse|null
     */
    public function getInternationalCost(RajaOngkirCostRequest $request)
    {
        try {

            return $this->client->requestAsync('POST', "api/v2/internationalCost",
                array_merge(
                    ['headers' => ['key' => 'c29168581f10f43d3eede488864a573c']],
                    ['form_params' => $request->all()]
                ))->then(
                function (ResponseInterface $res) {
                    return $this->json(Response::HTTP_OK, 'get international cost', $this->safeDecode($res->getBody()));
                },
                function (RequestException $e) {
                    return $this->jsonExceptions($e);
                }
            )->wait();

        } catch (\Exception $e) {
            return $this->jsonExceptions($e);
        }
    }


    /**
     * get cost for Wahana courier
     *
     * @param array $request
     * @return array
     */
    public function _getWahanaCosts(array $request)
    {
        try {
            if (strpos($request['destinationType'], 'city') !== false
                && strpos($request['courier'], 'wahana') !== false) {

                $existedDestination = AssetDistrict::find($request['destination']);
                if (!$existedDestination) {
                    throw AppException::inst(
                        Response::HTTP_NOT_FOUND,
                        "Wahana destination not faund.");
                }

                $request['destination'] = $existedDestination->priority_region->id;
                $request['destinationType'] = 'subdistrict';
                $request['courier'] = 'wahana';

                $promise = [
                    'wahana' => $this->client->requestAsync(
                        'POST',
                        "/api/cost",
                        array_merge(
                            ['headers' => ['key' => 'c29168581f10f43d3eede488864a573c']],
                            ['form_params' => $request]
                        ))
                ];
                $wahanaUnwrap = Promise\unwrap($promise);
                $wahana = $this->safeDecode($wahanaUnwrap['wahana']->getBody())['rajaongkir'];
                if (is_null($wahana['results']))
                    $wahana['results'] = [];
                $this->_transformExpectedCourier($wahana['results']);
                $this->_transformEtdAttribute($wahana['results']);

                return array_values($wahana['results']);
            }
            return [];
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return [];

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return [];
        }
    }

    /**
     * @param RajaOngkirCostRequest $request
     * @return \Illuminate\Http\JsonResponse|mixed|null
     * @throws \Throwable
     */
    public function getDomesticCosts(RajaOngkirCostRequest $request)
    {
        try {
            $arrRequest = $request->all();

            $promise = [
                'result' => $this->client->requestAsync(
                    'POST',
                    "/api/cost",
                    array_merge(
                        ['headers' => ['key' => 'c29168581f10f43d3eede488864a573c']],
                        ['form_params' => $arrRequest]
                    ))
            ];
            $resultUnwrap = Promise\unwrap($promise);
            $data = $this->safeDecode($resultUnwrap['result']->getBody())['rajaongkir'];
            if (is_null($data['results']))
                $data['results'] = [];
            $this->_transformExpectedCourier($data['results']);
            $this->_transformEtdAttribute($data['results']);
            $data['results'] = array_values($data['results']);
            $data['results'] = $this->_arrayValuesRecursive($data['results']);

////            merging array result
//            $data['results'] = array_merge(
//                $data['results'],
//                $this->_getWahanaCosts($arrRequest)
//            );

            //sorting
            $sortParam = $request->get('sort');
            if (!is_null($sortParam)) {
                $output = $this->_transformResult($data['results']);
                $sorted = collect($output)->sortBy('value');
                if (strtolower($sortParam) === 'dsc') {
                    $sorted = collect($output)->sortByDesc('value');
                }
                $data = [
                    'query' => $data['query'],
                    'status' => $data['status'],
                    'origin_details' => $data['origin_details'],
                    'destination_details' => $data['destination_details'],
                    'results' => $sorted->values()->all()
                ];
            }

            return $this->json(Response::HTTP_OK, 'get domestic cost', $data);

        } catch (\Exception $e) {
            return $this->jsonExceptions($e);
        }
    }

    /**
     * @param RajaOngkirWaybillRequest $request
     * @return \Illuminate\Http\JsonResponse|mixed|null
     */
    public function getWaybill(RajaOngkirWaybillRequest $request)
    {
        try {

            return $this->client->requestAsync(
                'POST',
                "/api/waybill",
                array_merge(
                    ['headers' => ['key' => 'c29168581f10f43d3eede488864a573c']],
                    ['form_params' => $request->all()]
                ))->then(
                function (ResponseInterface $res) use ($request) {
                    $data = $this->safeDecode($res->getBody())['rajaongkir'];

                    if (is_null($data)) {
                        throw AppException::inst(Response::HTTP_NOT_FOUND, "Failed Response");
                    }

                    //should throw exception
                    if ($data['status']['code'] === Response::HTTP_BAD_REQUEST) {
                        $data['status']['description'] = trim($data['status']['description'], 'Invalid waybill. ');
                        return AppException::inst(Response::HTTP_BAD_REQUEST, 'get waybill details', $data);
                    }

                    $sorted = array_reverse(array_sort($data['result']['manifest'], function ($value) {
                        $val = [$value['manifest_date'],
                            $value['manifest_time']];
                        return $val;
                    }));

                    $data = [
                        'query' => $data['query'],
                        'status' => $data['status'],
                        'result' => ['delivered' => $data['result']['delivered'],
                            'summary' => $data['result']['summary'],
                            'details' => $data['result']['details'],
                            'delivery_status' => $data['result']['delivery_status'],
                            'manifest' => $sorted]
                    ];

                    return $this->json(
                        Response::HTTP_OK,
                        'get waybill details',
                        $data);

                },
                function (RequestException $e) {
                    return $this->jsonExceptions($e);
                }
            )->wait();


        } catch (\Exception $e) {
            return $this->jsonExceptions($e);
        }
    }
}