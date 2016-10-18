<?php

class system_config
{
    const DIALY_REQUESTS_BY_CLIENT = 480;
    const REQUESTS_AT_SAME_TIME = 10;
    const DELAY_BETWEEN_REQUESTS = 0;
    const MIN_NEXT_ATTEND_TIME = 5 * 1000 * 60; // 5 min
    const REFERENCE_PROFILE_AMOUNT = 3;
    const INSTA_MAX_FOLLOWING =7000;
    CONST MIN_MARGIN_TO_INIT=1000;  //margen inicial requerido para trabajar con un cliente
    
    CONST NOT_INSTA_ID='j56iien@%ds';
    
    CONST SYSTEM_EMAIL='dumbu.system@gmail.com';
    CONST SYSTEM_USER_LOGIN='dumbu.system';
    CONST SYSTEM_USER_PASS='sorvete69@';

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