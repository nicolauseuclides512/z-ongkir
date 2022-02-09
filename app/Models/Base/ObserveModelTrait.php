<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace app\Models\base;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

trait ObserveModelTrait
{
    public static function bootObservable()
    {
        static::saved(function (BaseModel $model) {
            $model->created_by = 'anonymous';

            if (Auth::User())
                $model->created_by = Auth::User()->name;

            if ($model::$autoValidate) {
                $validation = Validator::make($model->attributes, $model::rules($model->id));

                #cheking validation
                if ($validation->fails()) {
                    $model->errors = $validation->messages();
                    return false;
                } else {
                    return true;
                }
            }
        });

        static::saving(function (BaseModel $model) {

            $model->created_by = 'anonymous';

            if (Auth::User())
                $model->created_by = Auth::User()->name;

            if ($model::$autoValidate) {
                $validation = Validator::make($model->attributes, $model::rules($model->id));

                #cheking validation
                if ($validation->fails()) {
                    $model->errors = $validation->messages();
                    return false;
                } else {
                    return true;
                }
            }
        });

        static::updated(function (BaseModel $model) {
            $model->updated_by = 'anonymous';

            if (Auth::User())
                $model->updated_by = Auth::User()->name;

        });

        static::updating(function (BaseModel $model) {
            $model->updated_by = 'anonymous';

            if (Auth::User())
                $model->updated_by = Auth::User()->name;

        });

        static::deleted(function (BaseModel $model) {
            $model->deleted_by = 'anonymous';

            if (Auth::User())
                $model->deleted_by = Auth::User()->name;

        });

        static::deleting(function (BaseModel $model) {
            $model->deleted_by = 'anonymous';

            if (Auth::User())
                $model->deleted_by = Auth::User()->name;

        });
    }
}