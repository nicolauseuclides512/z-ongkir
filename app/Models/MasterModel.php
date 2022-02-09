<?php
/**
 * @author Jehan Afwazi Ahmad <jee.archer@gmail.com>.
 */

namespace App\Models;


use App\Models\Base\BaseModel;


/**
 * Class KooralModel
 * Master of model, for customize every model func
 * @package App\Models
 */
class MasterModel extends BaseModel
{
    /**
     * @override boot
     * it will running validation rules in bootObservable class
     */
    protected static function boot()
    {
        parent::boot();
        self::bootObservable();
    }

    public static function inst()
    {
        return new self();
    }

}