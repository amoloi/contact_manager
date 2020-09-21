<?php

/**
 * @author lolkittens
 * @copyright 2012
 */

function client_code_genarator($CLIENT_NAME = null , $CLIENT_ID = null){
    //Create client name parts
    $NAME_PARTS = explode(' ' , $CLIENT_NAME);
    //Create a preffix holder aray
    
    //Stripping clent ID
    $CLIENT_ID = substr($CLIENT_ID , 1, 3);
    $NAME_PREFIX = array();
    
            define('POST_FIX' , 'I');
                    $NAME_PARTS_COUNT = count($NAME_PARTS);
                    while (list($key, $val) = each($NAME_PARTS))
                    {
                        switch($NAME_PARTS_COUNT){
                            case 1:
                                if(strtoupper($val) != 'CC'){
                                    $NAME_PREFIX[] = strtoupper(substr($val,0,2));
                                }  
                            break;
                            case 2:
                                if(strtoupper($val) != 'CC'){
                                    $NAME_PREFIX[] = strtoupper(substr($val,0,1));
                                }
                            break;
                            case 3:
                                if(strtoupper($val) != 'CC'){
                                    $NAME_PREFIX[] = strtoupper(substr($val,0,3));
                                } 
                            break;
                        } 
                    }
                    if(($NAME_PARTS_COUNT == 1) || $NAME_PARTS_COUNT == 2){
                        $NAME_PREFIX[] = POST_FIX;
                    }
                    
                    return implode('',$NAME_PREFIX) . $CLIENT_ID;
}

?>