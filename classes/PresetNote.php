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

namespace Genkiware\PresetNote\classes;

use \Db;
use \DBQuery;
use \ObjectModel;

class PresetNote extends ObjectModel
{
    /** @var $note Note content */
    public $note;

    /** @var $active Status */
    public $active;

    /** @var $date_add */
    public $date_add;

    /** @var $date_upd */
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = [
        'table' => 'genki_preset_note',
        'primary' => 'id_genki_preset_note',
        'fields' => [
            'note' => ['type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isMessage'],
            'active'    => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'date_add'  => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd'  => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];

    public static function getAllNotes() {
        $sql = new DbQuery();
        $sql->select('note');
        $sql->from('genki_preset_note', 'pn');
        $sql->orderBy('pn.note');

        return Db::getInstance()->executeS($sql);
    }

    public static function getNoteByOrderId($id_order) {
        $sql = new DbQuery();
        $sql->select('note_content');
        $sql->from('genki_preset_note_order', 'pno');
        $sql->where('id_order = ' . $id_order);

        return Db::getInstance()->getValue($sql);
    }

    public static function isRecordExist($id_order) {
        $sql = new DbQuery();
        $sql->select('id_order');
        $sql->from('genki_preset_note_order', 'pno');
        $sql->where('id_order = ' . $id_order);

        return (bool) Db::getInstance()->getValue($sql);
    }
}
