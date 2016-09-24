<?php

/**
 *
 * @author Cavinlz
 *        
 */
class cardcodesController extends \Controller
{

    public $dom = null;
    public $js = null;
    
    public function importOp()
    {
        
        $getstep = $this -> router -> get('step');
        $carid = $this -> router -> get('id');
        
        $data['currentstep'] = $getstep;
        
        if($getstep > 3 || empty($getstep))
        {
            //no more than 3 steps
            $data['currentstep'] = 1;
        }
        
        if($data['currentstep'] == 1) {
            $model = $this -> model('brands');
        
        }
        
        $data['cardid'] = $carid;
        CFactory::addApplicationStyleSheet(CFactory::getApplicationTemplateURL().'/../javascripts/plugins/fileinput/fileinput.css');
        CFactory::addApplicationStyleSheet(CFactory::getApplicationTemplateURL().'/styles/plugins/fuelux/wizard.css');
        
        $this -> Output('cardcodes',$data);
    }
    
    /**
     * Import Card Codes to certain wechat Card
     * 
     * 
     */
    public function loadOp()
    {
        $r = $this -> router;
        $way = $r -> post('ways');
        $id = $r -> get('id');
        
        $this->dom = 'progresslog';
        $this ->js = 'progressUpd';
        
        if($way == 'manual')
        {
            $codes = $r -> post('txt_codes');
            $codesArr = explode(',', $codes);
        }   
        elseif ($way == 'file')
        {
            self::progressUpdate('文件批量处理');
            
            $prodimg = $_FILES['prodimg'];
            
            $UpFileType = $prodimg['type'];
            $UpFileSize = $prodimg['size'];
            $UpFileTmpName = $prodimg['tmp_name'];
            $UpFileName = $prodimg['name'];
            $UpFileError = $prodimg['error'];
            
            if($UpFileType != 'text/plain'){
                
                self::progressUpdate('文件格式不正确 ! 操作终止 ...END',GENERAL_ERROR_RETURN_CODE);
                return false;
            
            }
            
            $FileName = 'card'.$id.'_'.date("YmdHis",time()+3600*8);
            $fileName = $FileName.'.txt';
            
            $uplpath = BasePath.DS.C('resource.temp_folder');
            
            $FileName = $uplpath.DS.$fileName;  
            if(!@move_uploaded_file($UpFileTmpName,$FileName)){
                self::progressUpdate('上传文件失败 ! 操作终止 ...END',GENERAL_ERROR_RETURN_CODE,true);
                return false;
            }
            //Read the file and return as an Array
            $codesArr = file($FileName);
            
        }
            //remove empty element 
            $codesArr = array_filter($codesArr);
        
            if(!$codesArr){
                
                self::progressUpdate('没有检测到任何券码数据! 操作终止 ...END',GENERAL_WARNING_RETURN_CODE,true);
                return false;
                
            }
            //remove duplicate records
            $codesArr = array_unique($codesArr);
            
            foreach ($codesArr as $val)
            {
                $temp[] = trimall($val);
            }
            
            $codesArr = $temp;
            
            /* Step 1: Firstly filter out the duplicate record at the local */
            $code_db = $this -> model();
            
            if($certianCardCodes = $code_db -> get_certain_card_codes($id))
            {
                $existingCodes = array();
                foreach ($certianCardCodes as $val)
                {
                    array_push($existingCodes, $val['code_no']);
                }
                /*取交集*/
                $filterArr = array_intersect($codesArr, $existingCodes);
                
                if($filterArr)
                {
                    self::progressUpdate('检测到已存在的卡券券码有 :'.implode(',', $filterArr),GENERAL_WARNING_RETURN_CODE);
                }
                $loop = count($codesArr);
                $newCodeArray = array();
                
                /*去除重复的 codes*/
                for($i = 0; $i<$loop;$i++)
                {
                    if(!in_array($codesArr[$i], $filterArr)){
                        $newCodeArray[] = $codesArr[$i];
                    }
                    
                }
                $codesArr = array_filter($newCodeArray);
            }
            
            $totalCodesReq = count($codesArr);
            
            /**
             * 单次调用接口传入code的数量上限为100个
             */
            
            self::progressUpdate('检测到有效的卡券券码有 '.$totalCodesReq.'个');
            
            if($totalCodesReq <= 0){
                self::progressUpdate('没有检测到任何有效的券码数据! 操作终止 ...END',GENERAL_WARNING_RETURN_CODE,true);
                return false;
            }
            
            $model = $this -> model('wechatcards');
            
            $cardInfo = $model -> get_card($id);

            $wechatcardid =  $cardInfo['wechat_card_id'];
            
            
            if($totalCodesReq > 100){
                self::progressUpdate('由于券码数据过多， 将采取分批导入 ...');
            }
            
            $currLoopIndex = 1;
            $beginIndex = 0;
            do{
                //$json = json_encode($paramsArray,JSON_UNESCAPED_UNICODE);
                
                $looping = $currLoopIndex * 100;
                
                $tempCodesArray = array();
                
                for($i=$beginIndex; $i < $looping; $i++)
                {
                    if($i >= $totalCodesReq) break;
                    
                    $tempCodesArray[] = $codesArr[$i];
                }
                
                $paramsArray = array(
                
                        'card_id'   =>  $wechatcardid,
                        'code'      =>  $tempCodesArray
                );
                
                
                self::progressUpdate('开始导入 Codes (Batch #'.$currLoopIndex.') =====>'); 
                
                
                $wechatApi = wechat::importCardCode($paramsArray);
                
                if(!$wechatApi) echo '<p class="text-contrast"> Error: '.$wechatApi['errmsg'].'['.$wechatApi['errcode'].']</p>';
                
                if($wechatApi['errcode'] === 0)   //Return Code 0  means Successfuly API Called
                {
                    
                    if($wechatApi['duplicate_code']){
                         self::progressUpdate('[Warning] 发现 重复导入的卡券 Codes :'.json_encode($wechatApi['duplicate_code']),GENERAL_WARNING_RETURN_CODE);
                    }
                    
                    if($wechatApi['succ_code']){
                        /* Perform the batch insertion in client */
                        $code_db -> batch_insert_cardcodes($wechatApi['succ_code'], array(
                                'cid'           =>$id,
                                'card_id'       =>$cardInfo['wechat_card_id'],
                                'code_status'   => CARD_CODE_STATUS_NORMAL,
                                'create_time'   => datetime(),
                                'indicator'     => $this->router->post('code_provider')
                                ));
                        
                    }
                    
                    $chkImportCountApi = wechat::checkImportedCodesCount($cardInfo['wechat_card_id']);
                    
                    if($chkImportCountApi['errcode'] > 0) self::progressUpdate('Error: '.$chkImportCountApi['errmsg'].'['.$chkImportCountApi['errcode'].']',GENERAL_ERROR_RETURN_CODE,true);
                    
                    self::progressUpdate('该卡券已成功导入 <b>'.$chkImportCountApi['count'].'</b> 个',GENERAL_SUCCESS_RETURN_CODE);
                    
                    self::progressUpdate('核对本次已存入的Codes列表 .. ');
                    
                    /*
                    $paramsArray = array(
                    
                            'card_id'   =>  $cardInfo['wechat_card_id'],
                            'code'      =>  $codesArr
                    );
                    */
                    
                    $chkImportListApi = wechat::getImportedCodesList($paramsArray);
                    
                    if($chkImportListApi['errcode'] > 0) self::progressUpdate('Error: '.$chkImportListApi['errmsg'].'['.$chkImportListApi['errcode'].']',GENERAL_ERROR_RETURN_CODE,true);
                    
                    self::progressUpdate('本次已经成功存入 Codes (个):'.count($chkImportListApi['exist_code']),GENERAL_SUCCESS_RETURN_CODE);
                    
                    self::progressUpdate('没有存入的 Codes :'.mysql_real_escape_string(json_encode($chkImportListApi['not_exist_code'])),GENERAL_ERROR_RETURN_CODE);
                    
                    $successCount = count($wechatApi['succ_code']);
                    
                    
                    if($successCount)
                    {
                        //若有超过1个卡券导入成功则更新库存
                        self::progressUpdate('更新卡券库存容量 ..');
                        
                        $updateQuantityApi = wechat::updateCardQuantity($cardInfo['wechat_card_id'],$successCount);
                        
                        if($updateQuantityApi['errcode'] > 0) self::progressUpdate('Error: '.$updateQuantityApi['errmsg'].'['.$updateQuantityApi['errcode'].']',GENERAL_ERROR_RETURN_CODE,true);
                        
                        /* Update Client DB Quantity Record*/
                        $model -> update_card(array('quantity'=>'`quantity`+'.$successCount),$id);
                        
                    }
                    
                
                    self::progressUpdate('===== 操作完毕 (Batch #'.$currLoopIndex.') ===== ');
                    
                }
                else 
                {
                    self::progressUpdate('=== WCP Returned Error: '.$wechatApi['errmsg'].'['.$wechatApi['errcode'].']  (Batch #'.$currLoopIndex.') ====',GENERAL_ERROR_RETURN_CODE,true);
                }
                
                $currLoopIndex++;
                $beginIndex = $looping;
              }while($looping < $totalCodesReq);
        
        
    }
    
    
    /**
     * @todo update the progress to be showed in the dashboard
     *
     * @param unknown $msg
     * @param string $code
     */
    protected function progressUpdate($msg, $code = '', $stop = false)
    {
        logg::debug($msg);
        $params = array($this->dom,$msg, $code);
        HttpResponse::jsPrompt($params, $this->js);
        if($stop){
            die();
        }
    }
    
   
    
}

?>