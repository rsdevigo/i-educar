<?php
#error_reporting(E_ALL);
#ini_set("display_errors", 1);
/**
 * i-Educar - Sistema de gestão escolar
 *
 * Copyright (C) 2006  Prefeitura Municipal de Itajaí
 *                     <ctima@itajai.sc.gov.br>
 *
 * Este programa é software livre; você pode redistribuí-lo e/ou modificá-lo
 * sob os termos da Licença Pública Geral GNU conforme publicada pela Free
 * Software Foundation; tanto a versão 2 da Licença, como (a seu critério)
 * qualquer versão posterior.
 *
 * Este programa é distribuí­do na expectativa de que seja útil, porém, SEM
 * NENHUMA GARANTIA; nem mesmo a garantia implí­cita de COMERCIABILIDADE OU
 * ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA. Consulte a Licença Pública Geral
 * do GNU para mais detalhes.
 *
 * Você deve ter recebido uma cópia da Licença Pública Geral do GNU junto
 * com este programa; se não, escreva para a Free Software Foundation, Inc., no
 * endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.
 *
 * @author    Lucas D'Avila <lucasdavila@portabilis.com.br>
 * @category  i-Educar
 * @license   @@license@@
 * @package   Portabilis
 * @since     Arquivo disponível desde a versão 1.1.0
 * @version   $Id$
 */

require_once 'lib/Portabilis/View/Helper/Input/Core.php';


/**
 * Portabilis_View_Helper_Input_MultipleSearchAjax class.
 *
 * @author    Lucas D'Avila <lucasdavila@portabilis.com.br>
 * @category  i-Educar
 * @license   @@license@@
 * @package   Portabilis
 * @since     Classe disponível desde a versão 1.1.0
 * @version   @@package_version@@
 */
class Portabilis_View_Helper_Input_MultipleSearchAjax extends Portabilis_View_Helper_Input_Core {

  public function multipleSearchAjax($objectName, $attrName, $options = array()) {
    $defaultOptions = array('options'            => array(),
                            'apiModule'         => 'Api',
                            'apiController'     => ucwords($objectName),
                            'apiResource'       => $objectName . '-search',
                            'searchPath'         => '');

    $options = $this->mergeOptions($options, $defaultOptions);

    if (empty($options['searchPath']))
      $options['searchPath'] = "/module/" . $options['apiModule'] . "/" . $options['apiController'] .
                               "?oper=get&resource=" . $options['apiResource'];

    // #TODO load resources value?

    /*
    // load value if received an resource id
    $resourceId = $options['hiddenInputOptions']['options']['value'];

    if ($resourceId && ! $options['options']['value'])
    $options['options']['value'] = $resourceId . " - ". $this->resourcesValue($resourceId);
    */

    $this->selectInput($objectName, $attrName, $options);

    $this->loadAssets();
    $this->js($objectName, $attrName, $options);
  }

  protected function selectInput($objectName, $attrName, $options) {
    $textHelperOptions = array('objectName' => $objectName);

    $this->inputsHelper()->select($attrName, $options['options'], $textHelperOptions);
  }


  protected function loadAssets() {
    $cssFile = '/modules/Portabilis/Assets/Plugins/Chosen/chosen.css';
    Portabilis_View_Helper_Application::loadStylesheet($this->viewInstance, $cssFile);

    // AjaxChosen requires this fixup, see https://github.com/meltingice/ajax-chosen
    $fixupCss = ".chzn-container .chzn-results .group-result { display: list-item; }";
    Portabilis_View_Helper_Application::embedStylesheet($this->viewInstance, $fixupCss);


    $jsFiles = array('/modules/Portabilis/Assets/Plugins/Chosen/chosen.jquery.min.js',
                     '/modules/Portabilis/Assets/Plugins/AjaxChosen/ajax-chosen.min.js',
                     '/modules/Portabilis/Assets/Javascripts/Frontend/Inputs/MultipleSearchAjax.js');
    Portabilis_View_Helper_Application::loadJavascript($this->viewInstance, $jsFiles);
  }


  protected function js($objectName, $attrName, $options) {
    // setup multiple search

    /*
      all search options (including the option ajaxChosenOptions, that is passed for ajaxChosen plugin),
      can be overwritten adding "var = multipleSearchAjax<ObjectName>Options = { 'options' : 'val', option2 : '_' };"
      in the script file for the resource controller.
    */

    $resourceOptions = "multipleSearchAjax" . Portabilis_String_Utils::camelize($objectName) . "Options";

    $js = "$resourceOptions = typeof $resourceOptions == 'undefined' ? {} : $resourceOptions;
           multipleSearchAjaxHelper.setup('$objectName', '$attrName', '" . $options['searchPath'] . "', $resourceOptions);";

    // this script will be executed after the script for the current controller (if it was loaded in the view);
    Portabilis_View_Helper_Application::embedJavascript($this->viewInstance, $js, $afterReady = true);
  }
}