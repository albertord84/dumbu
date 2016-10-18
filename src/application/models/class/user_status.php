<?php

class user_status
{
    const ACTIVE = 1;
    const BLOCKED_BY_PAYMENT = 2;
    const BLOCKED_BY_INSTA = 3;
    const DELETED = 4;
    const INACTIVE = 5;

    static public function Defines($const)
    {
        $cls = new ReflectionClass(__CLASS__);
        foreach($cls->getConstants() as $key=>$value)
        {
            if($value == $const)
            {
                return true;
            }
        }

        return false;
    }
}
