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

use Genkiware\PresetNote\classes\PresetNote;
use Genkiware\PresetNote\classes\GenkiTools;

class AdminPresetNoteController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'genki_preset_note';
        $this->className = 'Genkiware\PresetNote\classes\PresetNote';
        $this->lang = true;
        parent::__construct();

        $this->fields_list = [
            'id_genki_preset_note' => [
                'title' => $this->l('ID'),
                'align' => 'left',
                'class' => 'fixed-width-xs',
                'orderby' => true,
            ],
            'note' => [
                'title' => $this->l('Note Content'),
                'type' => 'text',
                'filter_key' => 'a!note',
            ],
            'active' => [
                'title' => $this->l('Active'),
                'type' => 'bool',
                'class' => 'fixed-width-sm',
                'active' => 'status',
                'align' => 'text-center',
                'filter_key' => 'a!active',
            ],
        ];

        $this->addRowAction('edit');
        $this->addRowAction('duplicate');
        $this->addRowAction('');
        $this->addRowAction('delete');

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?'),
                'icon' => 'icon-trash'
            ],
        ];
    }

    public function initPageHeaderToolbar() {
        if (empty($this->display)) {
            $this->page_header_toolbar_btn['new_genki_preset_note'] = array(
                'href' => self::$currentIndex . '&addgenki_preset_note&token=' . $this->token,
                'desc' => $this->l('Add new preset note'),
                'icon' => 'process-icon-new',
            );
        }

        parent::initPageHeaderToolbar();
    }

    public function renderForm() {        
        $table_title = ($this->display == 'add') ? $this->l('Create New Note') : $this->l('Edit Note');

        $this->fields_form = [
            'legend' => [
                'icon' => 'icon-pencil',
                'title' => $table_title,
            ],
            'input' => [
                [
                    'type' => 'textarea',
                    'label' => $this->l('Note'),
                    'name' => 'note',
                    'id' => 'note',
                    'col' => 6,
                    'lang' => true,
                    'required' => true,
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'id' => 'active',
                    'values' => [
                        ['value' => 1],
                        ['value' => 0],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Save'),
                'class' => 'btn btn-default pull-right'
            ],
        ];

        $this->addJS(_MODULE_DIR_ . $this->module->name . '/views/js/admin/form_create.js');

        return parent::renderForm();
    }

    public function getFieldsValue($obj) {
        if ($this->display == 'add') {
            $this->fields_value['active'] = 1;
        }
        return parent::getFieldsValue($obj);
    }
}
