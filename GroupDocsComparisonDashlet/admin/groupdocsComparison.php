<?php

require_once(KT_LIB_DIR . '/dispatcher.inc.php');
class GroupdocsComparisonDispatcher extends KTAdminDispatcher {
    // 
    function do_main () {    
        
        // SAVE FORM details in to 'gdcomparison'
        if($_POST['save']) {
            $save_arr[stripslashes(trim($_POST['file_id']))] = array(
                'embed_key'=>stripslashes(trim($_POST['embed_key'])),
                'file_id'=>stripslashes(trim($_POST['file_id'])), 
                'width'=>(isset($_POST['width']) && $_POST['width'])?$_POST['width']:'100%',
                'height'=>(isset($_POST['height']) && $_POST['height'])?(int)$_POST['height']:'300px',
                );
            $this->saveFileIds($save_arr);
        }
        
        // DELETE GroupDocs File ID 
        if( $_GET['del_id'] )  {
            $this->deleteFileId($_GET['del_id']);
            header('Location: admin.php?kt_path_info=groupdocs/comparison');
            exit;
        }
        
        // Get LIST of GD files from DB
        $rows = $this->getFileIds();
        $dataArray = array();
        if($rows) 
            foreach($rows as $row){
                if($row['file_id'] && $row['embed_key']){
                    if($row['width']==='0') $row['width'] = '';
                    if($row['height']==='0') $row['height'] = '';
                    $dataArray[$row['file_id']] = array( 
                        'embed_key'=>$row['embed_key'],
                        'file_id'=>$row['file_id'], 
                        'width'=>$row['width'], 
                        'height'=>$row['height'] );
                }
        }
        // Convert array to object for foreach in template
        $object = $this->arrayToObject($dataArray);
        // RENDER template
        $oTemplating =& KTTemplating::getSingleton();
        $oTemplate = $oTemplating->loadTemplate('see');
        $oTemplate->setData(array(
            'context' => $this,
            'dataArray' => $object,
            'pagelist' => '11',
        ));
        return $oTemplate;
    }
    
    
    
    /////////////////////////////////////////////////////////////////////////
        // Look in DB file 
        function getFileIds(){
                $arr_ol = (file_exists(dirname(__FILE__).'/../fileids.txt'))?file_get_contents(dirname(__FILE__).'/../fileids.txt'):'';
                $arr_old = array();
                if(substr($arr_ol,0,5)=='array'){
                    $arr_oldy = $arr_ol;
                    eval("\$arr_old = $arr_oldy;");
                }
                return $arr_old;
        }
        
        // Delete in DB file
        function deleteFileId($key=''){
                if(!$key) return false;
                $arr_ol = (file_exists(dirname(__FILE__).'/../fileids.txt'))?file_get_contents(dirname(__FILE__).'/../fileids.txt'):'';
                $arr_old = array();
                if(substr($arr_ol,0,5)=='array'){
                    $arr_oldy = $arr_ol;
                    eval("\$arr_old = $arr_oldy;");
                }
                $arrayEdit = $arr_old;
                unset($arrayEdit[stripslashes($key)]);
                $this->saveFileIds($arrayEdit, false);
        }
        
        // Save array in DB file
        function saveFileIds($arr=array(), $merge=true){
            if(is_array($arr)){
                // Old array
                $arr_old = $this->getFileIds();
                // New array
                $new_array = array();
               
                if(is_array($arr_old) && $merge){
                    $new_array = array_merge($arr_old,$arr);
                }else{
                    $new_array = $arr;
                    
                }
                file_put_contents(dirname(__FILE__).'/../fileids.txt', var_export($new_array, TRUE));
            }
            
        }
  ///// Convert array to object, for templates where {foreach  item=gdFile from=$dataArray} is present
  // helper function
  function array_to_obj($array, &$obj) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $obj->$key = new stdClass();
                $this->array_to_obj($value, $obj->$key);
            } else {
                $obj->$key = $value;
            }
        }
        return $obj;
    }
    // call function
    function arrayToObject($array) {
        $object = new stdClass();
        return $this->array_to_obj($array, $object);
    }
    ////////////////////////////////////////////////
}
