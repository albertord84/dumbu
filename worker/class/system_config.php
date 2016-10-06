<?php

class system_config
{
    const DIALY_REQUESTS_BY_CLIENT = 480;
    const REQUESTS_AT_SAME_TIME = 10;
    const DELAY_BETWEEN_REQUESTS = 0;
    const MIN_NEXT_ATTEND_TIME = 5 * 1000 * 60; // 5 min
    const REFERENCE_PROFILE_AMOUNT = 3;

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