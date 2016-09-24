<?php

/** 
 * @author Cavinlz
 * 
 */
class brandsController extends \Controller
{

    /**
     */
    
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
    
    
    public function updlikesOp()
    {
        $model = $this -> model();
         
        if($id = $this -> router -> post('k'))
        {
             
            $newArray = array(
                    'bid'    =>  $id,
                    'uid'      =>  User::get_user_session_Ukey(),
                    'openid'  =>  User::get_user_session_Openid()
            );
             
            if($this -> router -> post('type') == 'L')
            {
                //like the pet
                if($model -> setTable('brands_likes') -> insert($newArray))
                {
                    $code = GENERAL_SUCCESS_RETURN_CODE;
                    $userlikes = user_all_brand_likes(User::get_user_session_Openid());
                    $userlikes[] = $id;
                    user_all_brand_likes(User::get_user_session_Openid(),$userlikes);
                    
                    HttpResponse::setJsonOutput(array('code'=>$code));
                }
                
                
            }
            elseif($this -> router -> post('type') == 'U')
            {
                $where = array(
                        'bid' => $id,
                        'uid'  => User::get_user_session_Ukey(),
                        'openid'  =>  User::get_user_session_Openid(),
                );
                //unlike the pet
                if($model -> setTable('brands_likes') -> where($where) -> delete())
                {
                    $code = GENERAL_SUCCESS_RETURN_CODE;
                    
                    CMemory::mc_del('brandlikes_'.User::get_user_session_Openid());
                    
                    HttpResponse::setJsonOutput(array('code'=>$code));
                }
                
                
            }
        }
         
        $code = GENERAL_ERROR_RETURN_CODE;
        HttpResponse::setJsonOutput(array('code'=>$code));
    }
}

?>