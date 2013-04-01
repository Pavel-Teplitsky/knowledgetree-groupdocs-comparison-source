<?php

require_once(KT_LIB_DIR . '/dashboard/dashlet.inc.php');

class GroupdocsComparisonDashlet extends KTBaseDashlet 
{
    var $oUser;
    var $aUsers;
    var $interval = 5; //interval in minutes
    function GroupdocsComparisonDashlet ()
    {
        // set the title
        $this->sTitle = _kt('GroupDocs Comparison');
    }

    /**
     * Shall we display this dashlet for this user?
     */
    function is_active($oUser) 
    {
        $this->oUser = $oUser;
        return true;     
    }
      
    function render() 
    {
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
                        'height'=>$row['height'],
                        'cmsName'=>'KnowledgeTree',
                        'pluginVersion'=>'1.0');
                }
        }
        // Convert array to object for foreach in template
        $object = $this->arrayToObject($dataArray);
        // we must now render our dashlet.
        $oTemplating =& KTTemplating::getSingleton();
        $oTemplate = $oTemplating->loadTemplate('GroupdocsComparisonDashlet');
        $usercount = count($this->aUsers);
        $aTemplateData = array
        (
            'dataArray'=>$object,
        );

        return $oTemplate->render($aTemplateData);                
      }
      
          /////////////////////////////////////////////////////////////////////////
        // Look in DB file 
        function getFileIds(){
                $arr_ol = file_exists(dirname(__FILE__).'/fileids.txt')?file_get_contents(dirname(__FILE__).'/fileids.txt'):'';
                $arr_old = array();
                if(substr($arr_ol,0,5)=='array'){
                    $arr_oldy = $arr_ol;
                    eval("\$arr_old = $arr_oldy;");
                }
                return $arr_old;
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