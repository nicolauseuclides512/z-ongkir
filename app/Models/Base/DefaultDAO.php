<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Models\base;


use Exception;
use Illuminate\Support\Facades\DB;

trait DefaultDAO
{
    public function listAll($select = [], $status = 1)
    {
        return DB::table($this->table)
            ->where('status', $status)
            ->get($select ?? "");
    }

    public function storeExec($request)
    {
        DB::beginTransaction();
        try {
            $data = $this->populate($request);
            if (!$data->save()) {
                DB::rollback();
            }
            DB::commit();
            return $data;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function updateExec($request, $id)
    {
        DB::beginTransaction();
        try {
            $dataInId = $this->getByIdRef($id)->firstOrFail();
            $data = $this->populate($request, $dataInId);
            if (!$data->save()) {
                DB::rollback();
            }
            DB::commit();
            return $data;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroyExec($id)
    {
        DB::beginTransaction();
        try {
            $dataInId = $this->getByIdRef($id)->firstOrFail();
            if (!$dataInId->delete()) {
                DB::rollback();
            }
            DB::commit();
            return $dataInId;
        } catch (Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function destroySomeExec($ids)
    {
        $data = array_map(function ($id) {
            $dataInId = $this->getByIdRef($id)->first();
            if (!empty($dataInId)) {
                return array('errors' => "data by id $id not found");
            }
            if (!$dataInId->delete()) {
                return $dataInId;
            }
            return $dataInId;
        }, explode(',', preg_replace('/\s+/', '', $ids)));


        return $data;
    }

    public function markAsExec($ids, $status)
    {
        // TODO: Implement markAsExec() method.
    }
}