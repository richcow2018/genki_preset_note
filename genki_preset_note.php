<?php
/**
 * 2022 Genkiware
 *
 * NOTICE OF LICENSE
 *
 * This file is licenced under the GNU General Public License, version 3 (GPL-3.0).
 * With the purchase or the installation of the software in your application
 * you accept the licence agreement.
 *
 *  @author     Genkiware <info@genkiware.com>
 *  @copyright  2022 Genkiware
 *  @license    https://opensource.org/licenses/GPL-3.0 GNU General Public License version 3
 */

if (!defined('_PS_VERSION_')) exit;

require_once(dirname(__FILE__) . '/vendor/autoload.php');

use Genkiware\PresetNote\classes\PresetNote;
use Genkiware\PresetNote\classes\GenkiTools;

class Genki_Preset_Note extends \Module
{
    private $_html = '';
    private $_postErrors = array();
 
    public function __construct() {
        $this->name                   = 'genki_preset_note';
        $this->tab                    = 'front_office_features';
        $this->version                = '1.0';
        $this->author                 = 'Genkiware';
        $this->bootstrap              = true;
        $this->need_instance          = 0;
        $this->ps_versions_compliancy = ['min' => '1.7.7', 'max' => _PS_VERSION_];

        parent::__construct();

        $this->displayName            = $this->l('Genki Preset Note Manager');
        $this->description            = $this->l('This module allows you to manage preset note for invoice and DN');
        $this->confirmUninstall       = $this->l('Are you sure to uninstall this module?');
    }

    public function install(){
        return parent::install() &&
            $this->hooksRegistration() &&
            // $this->setConfigValues() &&
            $this->installDB() &&
            $this->addTab();
    }

    public function uninstall() {
        return parent::uninstall() &&
            // $this->removeConfigValues() && 
            $this->uninstallDB() &&
            $this->removeTab();
    }

    /**
     * Create custom table for saving all the FPS records
     * 
     * @return bool
     */
    private function installDB() {
        $db = Db::getInstance();

        $note_table = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'genki_preset_note` (
            `id_genki_preset_note` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `note` TEXT NOT NULL,
            `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT \'1\',
            `date_add` DATETIME NOT NULL,
            `date_upd` DATETIME NOT NULL,
            PRIMARY KEY (`id_genki_preset_note`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        $note_order_table = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'genki_preset_note_order` (
            `id_order` INT(10) UNSIGNED NOT NULL,
            `note_content` TEXT NOT NULL,
            PRIMARY KEY (`id_order`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';
        
        return $db->execute($note_table) &&
            $db->execute($note_order_table);
    }

    /**
     * Drop the custom table, not in use currently
     * 
     * @return bool
     */
    private function uninstallDB() {
        $db = Db::getInstance();

        $note_table = 'DROP TABLE `'._DB_PREFIX_.'genki_preset_note`';
        $note_order_table = 'DROP TABLE `'._DB_PREFIX_.'genki_preset_note_order`';
        
        return $db->execute($note_table) &&
            $db->execute($note_order_table);
    }

    /**
     * Register Hooks
     * 
     * @return bool Result
     */
    public function hooksRegistration() {
        $hooks = [
            'displayPDFInvoice',
            'displayPDFDeliverySlip',
            'displayAdminOrderSide',
            'actionAdminControllerSetMedia',
        ];

        return $this->registerHook($hooks);
    }

    // public function setConfigValues() {
    //     $res = true;

    //     $config = [
    //         'AWARD_IMG_FEATURE_ID' => '1',
    //     ];

    //     foreach ($config as $key => $value) {
    //         $res &= Configuration::updateValue($key, $value);
    //     }
        
    //     return $res;
    // }

    // public function removeConfigValues() {
    //     $res = true;

    //     $config = [
    //         'AWARD_IMG_FEATURE_ID',
    //     ];

    //     foreach ($config as $value) {
    //         $res &= Configuration::deleteByName($value);
    //     }
        
    //     return $res;
    // }

    private function addTab() {
        $res = true;
        $tabparent = "AdminParentOrders";
        $id_parent = Tab::getIdFromClassName($tabparent);
        $subtabs = [
            [
                'class'=>'AdminPresetNote',
                'name'=>'Preset Notes'
            ],
        ];
        foreach($subtabs as $subtab){
            $idtab = Tab::getIdFromClassName($subtab['class']);
            if(!$idtab){
                $tab = new Tab();
                $tab->active = 1;
                $tab->class_name = $subtab['class'];
                $tab->name = array();
                foreach (Language::getLanguages() as $lang){
                    $tab->name[$lang["id_lang"]] = $subtab['name'];
                }
                $tab->id_parent = $id_parent;
                $tab->module = $this->name;
                $res &= $tab->add();
            }
        }
        return $res;
    }

    private function removeTab() {
        $id_tabs = ["AdminPresetNote"];
        foreach($id_tabs as $id_tab){
            $idtab = Tab::getIdFromClassName($id_tab);
            $tab = new Tab((int)$idtab);
            $parentTabID = $tab->id_parent;
            $tab->delete();
            $tabCount = Tab::getNbTabs((int)$parentTabID);
            if ($tabCount == 0){
                $parentTab = new Tab((int)$parentTabID);
                $parentTab->delete();
            }
        }
        return true;
    }

    public function getContent() {
        $sql = new DbQuery();
        $sql->select('note_content');
        $sql->from('genki_preset_note_order', 'pno');
        $sql->where('id_order = 1');

        echo '<pre>';var_dump(Db::getInstance()->getValue($sql));echo '</pre>';exit;

        return 'asd';
    }

    /**
     * JS and CSS required for backoffice order details page
     */
    public function hookActionAdminControllerSetMedia($params) {
        if (!$this->active) return;

        if (strtolower(Tools::getValue('controller'))=="adminorders") {
            // Get Order ID, return if not found (i.e. Not in Order Detail page, but Order list page)
            $id_order = Tools::getValue('id_order');
            if (!$id_order) return;

            // Media::addJsDef([
            //     'id_order' => $id_order,
            //     'fps_state' => $fps_state,
            //     'payment_record_form' => $payment_record_form,
            //     'redirect_link_base' => $redirect_link_base,
            // ]);

            $this->context->controller->addJS($this->_path . 'views/js/admin/admin_order_form.js');
        }
    }

    public function hookDisplayAdminOrderSide($params) {
        if (!$this->active) return;

        // If add / update action is done
        if (Tools::getValue('genki_note') == '0') {
            $this->get('session')->getFlashBag()->add('error', 'Error when updating data. Please try again');
        } else {
            $this->get('session')->getFlashBag()->add('success', 'Order note updated successfully');
        }
        
        $notes = PresetNote::getAllNotes();
        
        $form_action = 'UpdateOrderNote';
        $note_content = PresetNote::getNoteByOrderId($params['id_order']);
        if (!$note_content) {
            $note_content = '';
            $form_action = 'AddOrderNote';
        }
        
        $this->context->smarty->assign([
            'id_order' => $params['id_order'],
            'notes' => $notes,
            'note_config' => $this->_path . 'index.php?controller=AdminModules&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'note_content' => $note_content,
            'form_action' => $this->context->link->getAdminLink('AdminPresetNote', true, [], ['action' => $form_action]),
            'order_link' => $this->context->link->getAdminLink('AdminOrders', true, ['route' => 'admin_orders_view', 'orderId' => $params['id_order']]),
        ]);

        return $this->fetch($this->local_path . 'views/templates/admin/preset_note_box.tpl');
    }

    public function getOrderNote($id_order) {
        return PresetNote::getNoteByOrderId($id_order);
    }

    public function hookDisplayPDFInvoice($params) {
        return nl2br($this->getOrderNote($params['object']->id_order));
    }

    public function hookDisplayPDFDeliverySlip($params) {
        return nl2br($this->getOrderNote($params['object']->id_order));
    }
}
