<?php
/*
 * MundiAPILib
 *
 * This file was automatically generated by APIMATIC v2.0 ( https://apimatic.io ).
 */

namespace MundiAPILib\Models;

use JsonSerializable;

/**
 * Request for updating a customer
 */
class UpdateCustomerRequest implements JsonSerializable
{
    /**
     * Name
     * @required
     * @var string $name public property
     */
    public $name;

    /**
     * Email
     * @required
     * @var string $email public property
     */
    public $email;

    /**
     * Document number
     * @required
     * @var string $document public property
     */
    public $document;

    /**
     * Person type
     * @required
     * @var string $type public property
     */
    public $type;

    /**
     * Address
     * @required
     * @var CreateAddressRequest $address public property
     */
    public $address;

    /**
     * Metadata
     * @required
     * @var array $metadata public property
     */
    public $metadata;

    /**
     * @todo Write general description for this property
     * @var CreatePhonesRequest|null $phones public property
     */
    public $phones;

    /**
     * Constructor to set initial or default values of member properties
     * @param string               $name     Initialization value for $this->name
     * @param string               $email    Initialization value for $this->email
     * @param string               $document Initialization value for $this->document
     * @param string               $type     Initialization value for $this->type
     * @param CreateAddressRequest $address  Initialization value for $this->address
     * @param array                $metadata Initialization value for $this->metadata
     * @param CreatePhonesRequest  $phones   Initialization value for $this->phones
     */
    public function __construct()
    {
        if (7 == func_num_args()) {
            $this->name     = func_get_arg(0);
            $this->email    = func_get_arg(1);
            $this->document = func_get_arg(2);
            $this->type     = func_get_arg(3);
            $this->address  = func_get_arg(4);
            $this->metadata = func_get_arg(5);
            $this->phones   = func_get_arg(6);
        }
    }


    /**
     * Encode this object to JSON
     */
    public function jsonSerialize()
    {
        $json = array();
        $json['name']     = $this->name;
        $json['email']    = $this->email;
        $json['document'] = $this->document;
        $json['type']     = $this->type;
        $json['address']  = $this->address;
        $json['metadata'] = $this->metadata;
        $json['phones']   = $this->phones;

        return $json;
    }
}
