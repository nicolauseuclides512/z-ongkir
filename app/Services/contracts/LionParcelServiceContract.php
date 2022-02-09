<?php
/**
 * @author Jehan Afwazi Ahmad <jehan.afwazi@gmail.com>.
 */


namespace App\Services\contracts;


interface LionParcelServiceContract
{
    public function shippingCost(array $request);

    public function trackShipment(array $request);
}