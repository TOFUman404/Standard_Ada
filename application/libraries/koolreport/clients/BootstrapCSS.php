<?php
/**
 * This file when used in KoolReport will add Bootstrap CSS to view.
 *
 * @author KoolPHP Inc (support@koolphp.net)
 * @link https://www.koolphp.net
 * @copyright KoolPHP Inc
 * @license https://www.koolreport.com/license#mit-license
 */

namespace koolreport\clients;
use \koolreport\core\Utility;

trait BootstrapCSS
{
    public function __constructBootstrapCSS()
    {
        $this->registerEvent('OnResourceInit',function(){
            $bootstrapAssetUrl = $this->publishAssetFolder(dirname(__FILE__)."/bootstrap");
            $this->getResourceManager()->addCssFile($bootstrapAssetUrl."/css/bootstrap.min.css");
            $this->getResourceManager()->addCssFile($bootstrapAssetUrl."/css/bootstrap-theme.min.css");
        });
    }
}