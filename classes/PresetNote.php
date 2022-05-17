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

namespace Genkiware\PresetNote\classes;

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
        'multilang' => true,
        'fields' => [
            'note' => ['type' => self::TYPE_STRING, 'required' => true, 'validate' => 'isMessage', 'lang' => true],
            'active'    => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'date_add'  => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
            'date_upd'  => ['type' => self::TYPE_DATE, 'validate' => 'isDate'],
        ],
    ];
}
