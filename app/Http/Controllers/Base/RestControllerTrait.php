<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Http\Controllers\Base;

use App\Exceptions\AppException;
use Exception;
use Illuminate\Http\Response;

/**
 * Trait RestControllerTrait
 * default controller function
 * @package App\Http\Controllers\Base
 */
trait RestControllerTrait
{
    /**
     * internal function. this will call in create and edit function
     * @return array
     */
    protected function _resource()
    {
        return [];
    }

    /**
     * show data list with filter, sorting and pagination
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    protected function index()
    {
        $data = $this->model
            ->filter($this->requestTransl()['filter_by'], $this->requestTransl()['q'])
            ->orderBy(
                $this->requestTransl()['sort_column'],
                $this->requestTransl()['sort_order'])
            ->paginate($this->request->input("per_page"));

        return $this->json(Response::HTTP_OK, 'success', $data);
    }

    /**
     * show data when create
     * @return mixed
     */
    protected function create()
    {
        return $this->json(Response::HTTP_OK, 'success', $this->_resource());
    }

    /**
     * store input data
     * @return mixed
     */
    protected function store()
    {
        try {
            $data = $this->model->storeExec($this->request->input());

            if (isset($data->errors) || isset($data->errorInfo)) {
                throw AppException::inst(Response::HTTP_BAD_REQUEST, "Save $this->name is failed.", $data);
            }

            return $this->json(Response::HTTP_CREATED, "Save $this->name is successfully.", $data);
        } catch (Exception $e) {
            return $this->jsonExceptions($e);
        }
    }

    /**
     * show data by id
     * @param $id
     * @return mixed
     */
    protected function show($id)
    {
        try {
            return $this->json(Response::HTTP_OK, 'success', $this->model->getByIdRef((int)$id)->firstOrFail());

        } catch (Exception $e) {
            return $this->jsonExceptions($e);
        }
    }

    /**
     * show edit data by id
     * @param $id
     * @return mixed
     */
    protected function edit($id)
    {
        try {
            $data = $this->_resource();
            $data['data'] = $this->model->getByIdRef($id)->firstOrFail();

            return $this->json(Response::HTTP_OK, 'success', $data);
        } catch (Exception $e) {
            return $this->jsonExceptions($e);
        }
    }

    /**
     * update data from input by particular id
     * @param $id
     * @return mixed
     */
    protected function update($id)
    {
        try {
            $data = $this->model->updateExec($this->request->input(), $id);

            if (isset($data->errors) || isset($data->errorInfo)) {
                throw AppException::inst(Response::HTTP_BAD_REQUEST, "update $this->name is failed.", $data);
            }
            return $this->json(Response::HTTP_CREATED, "update $this->name is successfully.", $data);
        } catch (Exception $e) {
            return $this->jsonExceptions($e);
        }
    }

    /**
     * remove data by id
     * @param $id
     * @return mixed
     */
    protected function destroy($id)
    {
        try {
            $data = $this->model->destroyExec($id);

            if (isset($data->errors)) {
                throw AppException::inst(Response::HTTP_BAD_REQUEST, "delete $this->name is failed.", $data);
            }

            return $this->json(Response::HTTP_OK, "delete $this->name is successfully.", $data);
        } catch (Exception $e) {
            return $this->jsonExceptions($e);
        }
    }

    /**
     * show data list
     * @return mixed
     */
    protected function list()
    {
        $list = $this->model->listAll();
        return $this
            ->json(Response::HTTP_OK,
                "Fetch $this->name",
                $list
            );
    }
}