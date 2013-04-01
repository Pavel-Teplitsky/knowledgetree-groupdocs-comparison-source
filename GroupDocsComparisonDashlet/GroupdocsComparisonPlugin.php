<?php

require_once(KT_LIB_DIR . '/plugins/plugin.inc.php');
require_once(KT_LIB_DIR . '/plugins/pluginregistry.inc.php'); 

class GroupdocsComparisonPlugin extends KTPlugin
{
        var $sNamespace = 'groupdocscomparisonplugin.plugin';
        
        function UserOnlinePlugin($sFilename = null) 
        {
               $res = parent::KTPlugin($sFilename);
               $this->sFriendlyName = _kt('GroupdocsComparison Plugin');
               return $res;
        }

        function setup() 
        {
               $this->registerDashlet('GroupdocsComparisonDashlet', 'groupdocscomparisondashlet.dashlet', 'GroupdocsComparisonDashlet.php');

               require_once(KT_LIB_DIR . "/templating/templating.inc.php");
               $oTemplating =& KTTemplating::getSingleton();
               $oTemplating->addLocation('GroupdocsComparison Plugin', '/plugins/GroupdocsComparisonDashlet/templates');
               $this->setupAdmin();
       }

    function setupAdmin() {
	$this->registerAdminCategory('groupdocscomparison', _kt('GroupDocs Comparison'),
            _kt('GroupDocs is a next generation Document Management solution that makes it easier for businesses to collaborate, share and work with documents online. So, organise, view, annotate, compare, assemble and share all your documents with KnowledgeTree'));
        
        $this->registerAdminPage("comparison", 'GroupdocsComparisonDispatcher', 'groupdocscomparison',
            _kt('Add GroupDocs  Comparison  ID'), _kt('To view GroupDocs Comparison in dashlets you need to add GroupDocs  Embed Key and File ID'),
            'admin/groupdocsComparison.php', null);
        
			
    }
}

$oPluginRegistry =& KTPluginRegistry::getSingleton();
$oPluginRegistry->registerPlugin('GroupdocsComparisonPlugin', 'groupdocscomparisonplugin.plugin', __FILE__);