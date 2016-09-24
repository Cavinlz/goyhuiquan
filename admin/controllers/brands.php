<?php

/** 
 * @author Cavinlz
 * 
 */
class brandsController extends \Controller
{

    /**
     */
    public function createOp ()
    {
        $getstep = $this -> router -> get('step');
        
        $data['currentstep'] = $getstep;
        
        if($getstep > 2 || empty($getstep))
        {
            //no more than 3 steps
            $data['currentstep'] = 1;
        }
        
        if($data['currentstep'] == 1){
            $this -> Output('choose');
        }
        else{
            
            $state = $this -> router -> get('state');
            
            $data['state'] = ($state == 'auth') ? 'Y' : 'N';
            
            CFactory::addApplicationStyleSheet(CFactory::getApplicationTemplateURL().'/../javascripts/plugins/fileinput/fileinput.css');
            $this -> Output('new.form',$data);
        }
            
        
    }
    
    
    public function createbrandOp()
    {
        addFunc('imgoperator');
        
        //upload to temp folder firstly
        $uplpath = BasePath.DS.C('resource.temp_folder');
        
        $img_init = array(
                 
                'path'		=>	$uplpath,
                'name_pref' =>	'LG_'     //pic name's prefix
        
        );
        
        $imgObj = new imgoperator($img_init);
        
        $prodimg = $_FILES['prodimg'];
        
        if(!$imgObj -> UploadFile($prodimg)){
           
            $this -> redirect_rsp_page($imgObj -> responseText, $imgObj -> errorReturnCode);
        }
        else {
             
            $filename = $prd_info['pic'] = $imgObj -> get_uploaded_file_name();
            $target_folder = BasePath.DS.C('resource.brand_logo_path');
            $model = $this->model();
            
            $insertArr = array(
                    
                   'brand_name' =>  $this -> router -> post('brand_name'), 
                   'logo_name'  =>  $filename,
                   'datetime'  =>  datetime()
                    
            );
            
            if($auth = $this -> router -> post('auth')){
            
                $insertArr['brand_id'] = $this -> router -> post('brand_id');
                $insertArr['auth_flag'] = 1;
                $insertArr['auth_expired_date'] = $this -> router -> post('expired_date');
                
            }
            
            
            if($id = $model -> insert($insertArr)){
                
                $dirname = $target_folder.DS.$id;
                
                if(!is_dir($dirname)) {
                    
                    mkdir($dirname, 0755, true);
                }
                
                if(rename($uplpath.DS.$filename, $dirname.DS.$filename))
                {
                    $code = GENERAL_SUCCESS_RETURN_CODE; 
                }
                else 
                {
                    $code = GENERAL_WARNING_RETURN_CODE;
                    $msg = '注意: 品牌已被添加， 但logo文件移动失败，请联系管理员手动复制。';
                }
                
                $this -> redirect_rsp_page($msg, $code);
            }
        
        }
    }
    
    /**
     * 品牌管理
     *
     */
    public function listviewOp()
    {
    
        $model = $this -> model();
    
        $filter = $this -> get_filter_condition();
    
        //$filters = array_merge($basic_filter, $filter);
    
        $data = $model -> get_list($this -> router ->get('p'), $filter);
        
        $this -> view('brandlist',false);
        $this -> Output('table',$data);
    }
    
    


    protected function get_filter_condition()
    {
        $session = $this -> load('session');
    
        $filter = array();
    
        $trigger = $this->router-> post('search');
        $page = $this->router->get('p');
    
        if(isset($trigger))
        {
    
            unset($session -> data['condition']);
            unset($session -> data['search']);
    
    
    
            if($filter) $session -> data['condition'] = $filter;
             
            //print_r($filter);
    
        }
        elseif(!empty($page))
        {
            if($session->data['condition'])
                $filter = $session->data['condition'];
        }
        else
        {
            unset($session -> data['condition']);
            unset($session -> data['search']);
        }
    
        return $filter;
    }
    
    
    public function getimgOp()
    {
        $id = $this -> router -> get('s');
        $name = $this -> router -> get('n');
        $target_folder = BasePath.DS.C('resource.brand_logo_path');
        
        $image = $target_folder.DS.$id.DS.base64_decode($name);
        
        if(file_exists($image)){
            header('Content-Type: image/jpeg');
            readfile($image);
        }else{
            die('Pic not found');	//图片无法找到
        }
    }
    
    
}

?>