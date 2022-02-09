<?php
/**
 * @author Jehan Afwazi Ahmad <jehan.afwazi@gmail.com>.
 */


namespace App\Services\contracts;


interface RajaOngkirServiceContract
{
    public function shippingCosts(array $request);

    public function trackShipments(array $request);
}