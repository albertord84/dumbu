<?php
/*
 * MundiAPILib
 *
 * This file was automatically generated by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace MundiAPILib\Models;

use JsonSerializable;

/**
 * Response object for getting a pricing scheme
 */
class GetPricingSchemeResponse implements JsonSerializable
{
    /**
     * @todo Write general description for this property
     * @required
     * @var integer $price public property
     */
    public $price;

    /**
     * @todo Write general description for this property
     * @required
     * @maps scheme_type
     * @var string $schemeType public property
     */
    public $schemeType;

    /**
     * @todo Write general description for this property
     * @required
     * @maps price_brackets
     * @var GetPriceBracketResponse[] $priceBrackets public property
     */
    public $priceBrackets;

    /**
     * @todo Write general description for this property
     * @maps minimum_price
     * @var integer|null $minimumPrice public property
     */
    public $minimumPrice;

    /**
     * Constructor to set initial or default values of member properties
     * @param integer $price         Initialization value for $this->price
     * @param string  $schemeType    Initialization value for $this->schemeType
     * @param array   $priceBrackets Initialization value for $this->priceBrackets
     * @param integer $minimumPrice  Initialization value for $this->minimumPrice
     */
    public function __construct()
    {
        if (4 == func_num_args()) {
            $this->price         = func_get_arg(0);
            $this->schemeType    = func_get_arg(1);
            $this->priceBrackets = func_get_arg(2);
            $this->minimumPrice  = func_get_arg(3);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['price']          = $this->price;
        $json['scheme_type']    = $this->schemeType;
        $json['price_brackets'] = $this->priceBrackets;
        $json['minimum_price']  = $this->minimumPrice;

        return $json;
    }
}
