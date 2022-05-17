<?php
/**
 * 2020-2022 Genkiware
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 *
 *  @author     Genkiware <info@genkiware.com>
 *  @copyright  2022 Genkiware
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) exit;

require_once(dirname(__FILE__) . '/vendor/autoload.php');

use Genkiware\PresetNote\classes\PresetNote;
use Genkiware\PresetNote\classes\GenkiTools;

class Genki_Preset_Note extends Module
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
        $this->ps_versions_compliancy = ['min' => '1.7', 'max' => _PS_VERSION_];

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
        $note_table = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'genki_preset_note` (
            `id_genki_preset_note` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT \'1\',
            `date_add` DATETIME NOT NULL,
            `date_upd` DATETIME NOT NULL,
            PRIMARY KEY (`id_genki_preset_note`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

        $note_lang_table = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'genki_preset_note_lang` (
            `id_genki_preset_note` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
            `id_lang` INT(10) UNSIGNED NOT NULL,
            `note` TEXT NOT NULL,
            PRIMARY KEY (`id_genki_preset_note`, `id_lang`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';
        
        return Db::getInstance()->execute($note_table) &&
            Db::getInstance()->execute($note_lang_table);
    }

    /**
     * Drop the custom table, not in use currently
     * 
     * @return bool
     */
    private function uninstallDB() {
        $note_table = 'DROP TABLE `'._DB_PREFIX_.'genki_preset_note`';
        $note_lang_table = 'DROP TABLE `'._DB_PREFIX_.'genki_preset_note_lang`';
        
        return Db::getInstance()->execute($note_table) &&
            Db::getInstance()->execute($note_lang_table);
    }

    /**
     * Register Hooks
     * 
     * @return bool Result
     */
    public function hooksRegistration() {
        $hooks = [
            'displayPDFInvoice',
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
}
