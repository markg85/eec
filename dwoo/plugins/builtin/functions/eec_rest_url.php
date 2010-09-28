<?php

/**
 * Send a rest URL to the EEC Rest handler which will either return a rest URL or a PHP argument URL.
 *
 * @author     Mark <markg85@gmail.com>
 * @copyright  Copyright (c) 2010, markg85
 */

function Dwoo_Plugin_eec_rest_url(Dwoo $dwoo, $value)
{
    $oRestHandling = new REST_handling();
    $oRestHandling->setRestEnabled(Core::getInstance()->get("rest_handling")->getRestEnabled());
    $oRestHandling->setRestUrl($value);
    
    return $oRestHandling->getUrl();
}
