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
            `id_order` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
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
            'displayAdminOrderSide',
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
        $pn = new PresetNote(1);
        echo '<pre>';var_dump($pn);echo '</pre>';exit;

        return 'asd';
    }
}
