<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Models\base;


interface BaseModelContract
{
    public static function inst();

    public static function rules($id = null);

    public function populate($request, BaseModel $model = null);

    public function scopeFilter($q, $filterBy, $query);

    public function storeExec($request);

    public function updateExec($id, $request);

    public function destroyExec($ids);

    public function markAsExec($ids, $status);
}