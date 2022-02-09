<?php
/**
 * @author Jehan Afwazi Ahmad <jehan.afwazi@gmail.com>.
 */


namespace App\Services;


use App\Cores\Jsonable;
use App\Cores\ZHttpClient;
use App\Exceptions\AppException;
use App\Http\Requests\RajaOngkirCostRequest;
use App\Services\contracts\LionParcelServiceContract;
use App\Services\contracts\RajaOngkirServiceContract;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use GuzzleHttp\Promise;
use Illuminate\Http\Response;
use Psr\Http\Message\ResponseInterface;

class RajaOngkirService implements RajaOngkirServiceContract
{

    use Jsonable;

    private $client;
    private $header;

    public function __construct()
    {
        $this->client = ZHttpClient::init(config("gateway.connection.raja_ongkir.api_url"));
        $this->header = ['headers' => [
            'key' => config("gateway.connection.raja_ongkir.api_key")
        ]];

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

                            if (strpos($cost['etd'], 'HARI') === false
                                && strpos($cost['etd'], 'JAM') === false
                                && strpos($cost['etd'], 'Hari') === false
                                && strpos($cost['etd'], 'Jam') === false) {

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
            if (strpos($kurir['code'], 'jne') === false
                && strpos($kurir['code'], 'pos') === false
                && strpos($kurir['code'], 'tiki') === false
                && strpos($kurir['code'], 'J&T') === false
                && strpos($kurir['code'], 'wahana') === false
                && strpos($kurir['code'], 'pandu') === false
                && strpos($kurir['code'], 'sicepat') === false) {

                unset($data[$key]);
            } else {
                foreach ($kurir['costs'] as $subkey => &$costs) {
                    if (strpos($costs['service'], 'ONS') === false
                        && strpos($costs['service'], 'REG') === false
                        && strpos($costs['service'], 'Paket Kilat Khusus') === false
                        && strpos($costs['service'], 'Express Next Day Barang') === false
                        && strpos($costs['service'], 'EZ') === false
                        && strpos($costs['service'], 'OKE') === false
                        && strpos($costs['service'], 'YES') === false
                        && strpos($costs['service'], 'DES') === false
                        && strpos($costs['service'], 'BEST') === false
                        && strpos($costs['service'], 'CTC') === false) {

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
     * @param array $request
     * @return array
     * @throws \Exception
     * @throws \Throwable
     */
    public function shippingCosts(array $request)
    {
        try {
            //exclude lion parcel
            $request['courier'] = str_replace(":lp", "", $request['courier']);

            $requestParam = array_merge(
                $this->header,
                ['form_params' => $request]
            );

            $promise = [
                'result' => $this->client
                    ->requestAsync(
                        'POST',
                        $this->client->url("/cost"),
                        $requestParam
                    )];

            $resultUnwrap = Promise\unwrap($promise);

            if (empty($resultUnwrap['result']))
                throw AppException::inst(
                    Response::HTTP_NOT_FOUND,
                    "Failed Response"
                );

            $data = $this->safeDecode($resultUnwrap['result']->getBody())['rajaongkir'];

            if (is_null($data['results']))
                $data['results'] = [];

            $this->_transformExpectedCourier($data['results']);
            $this->_transformEtdAttribute($data['results']);
            $data['results'] = array_values($data['results']);
            $data['results'] = $this->_arrayValuesRecursive($data['results']);

            //sorting
            $sortParam = $request['sort'] ?? "dsc";
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

            return $data;

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
    public function trackShipments(array $request)
    {
        try {

            $requestParam = array_merge(
                $this->header,
                ['form_params' => $request]
            );

            $promise = $this->client
                ->requestAsync(
                    'POST',
                    $this->client->url("/waybill"),
                    $requestParam
                );

            $resultUnwrap = Promise\unwrap($promise);

            if (empty($resultUnwrap['result']))
                throw AppException::inst(
                    Response::HTTP_NOT_FOUND,
                    "Failed Response"
                );

            $data = $this->safeDecode($resultUnwrap['result']->getBody())['rajaongkir'];

            if (is_null($data)) {
                throw AppException::inst(
                    Response::HTTP_NOT_FOUND,
                    "Failed Response"
                );
            }

            //should throw exception
            if ($data['status']['code'] === Response::HTTP_BAD_REQUEST) {
                $data['status']['description'] = trim($data['status']['description'], 'Invalid waybill. ');
                throw AppException::inst(
                    Response::HTTP_BAD_REQUEST,
                    'get waybill details',
                    $data
                );
            }

            $sorted = array_reverse(
                array_sort($data['result']['manifest'],
                    function ($value) {
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

            return $data;

        } catch (\Exception $e) {
            throw $e;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

}