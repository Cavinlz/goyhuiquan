<?php

class apiController extends \Controller
{
    public function baiduOp()
    {
        $latitude = $this -> router -> post('la'); 
        $longtitude = $this -> router -> post('lg');
        
        addFunc('CurlRequestHandler');
        
        $api = "http://api.map.baidu.com/geocoder/v2/?ak=MVRLzbYKOOGfmwobnWZGXgGS&location=$latitude,$longtitude&output=json";
        //logg::debug($api);
        $curl = new CurlRequestHandler($api);
        
        $data = $curl -> do_curl_get_request();
        
        if($data === FALSE)
        {
            //in case failed in the Curl Request
            $data = file_get_contents($api);
        }
        
        //logg::debug($data);
        
        $returnArray = json_decode($data,true);
        
        if($returnArray['status'] === 0)
        {
            $code = GENERAL_SUCCESS_RETURN_CODE;
            $record = $returnArray['result']['addressComponent'];
           // $record = array('province'=>$returnArray['addressComponent']['province']);
           
            $city = $record['city'];
            $session = $this -> load('session');
            
            
            /**
             * Firstly check if any exisitng cities matches the return value
             * Otherwise go to do rawly checking (ie.like) get the value 
             * 
             */
            CMemory::load_cache_file('data/city');
            $model = $this -> model();
            
            if($citys = CMemory::get('city'))
            {
                if($key = array_search($city, $citys))
                {
                    logg::debug('matches by default');
                    //$session -> set_filter('CITY',$key);
                   // $session -> set_filter('CITY_NAME',$city);
                    $found = true;
                }
                else
                {
                    logg::system('unable to find city value in the array :'.$city);
                }
            }
            
            if(!$found)
            {
                if($rs = $model -> select('*') -> where(array('area_name#LIKE'=>$city)) -> getOne())
                {
                    $key = $rs['id'];
                    //$session -> set_filter('CITY',$key);
                    //$session -> set_filter('CITY_NAME',$city);
                }
                else
                {
                    logg::system('unable to find city value in db :'.$city);
                }
            }
            
            /**
             * if matches in the system city key then return
             */
            if($key){
                $record['unique'] = $key;
            }
            
            /**
             * Followign flag was recorded the visitor's current city no matter he has chose any other
             */
            $session -> data['YZ_MY_CURR_CITY'] = $city;
            $session -> data['YZ_MY_CURR_CITY_ID'] = $key;
        }
        else 
        {
            $code = $returnArray['status'];
            $record = 'Unable to get the location.';
        }
        
        $data = array('code'=>$code, 'body'=>$record);
        //logg::debug(json_encode($data));
        HttpResponse::setJsonOutput($data);
    }
    
    /**
     * Following function is disabled. It was used to update the city-prov table with the pinyin
     * 
     * @return boolean
     */
    public function updOp()
    {
        return false;
        $model = $this -> model('member');
        $model -> upd_pro();
    }
    
    public function getgeoOp()
    {
        //经度
        $longitude = $this -> router -> post('lg');
        //纬度
        $latitude = $this -> router -> post('lt');
        
        //logg::debug('longitude =>'. $longitude);
        //logg::debug('latitude =>'. $latitude);
        
        $model = $this -> model('members');
        
        $array = array(
                'lg' => $longitude,
                'lt' => $latitude
        );
        
        $model -> setUpdate(array('last_geo' => json_encode($array))) ->where(array('openid'=>User::get_user_session_Openid()))->update();
        
        $data = array('code'=>GENERAL_SUCCESS_RETURN_CODE);
        $session = $this -> load('session');
        $session -> data['YHG_MY_CURR_GEO'] = json_encode($array);
        //logg::debug(json_encode($data));
        HttpResponse::setJsonOutput($data);
    }
}

?>