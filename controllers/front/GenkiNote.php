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

class Genki_Preset_NoteGenkiNoteModuleFrontController extends ModuleFrontController
{
    public function postProcess() {
        $action = Tools::getValue('action');

        switch ($action) {
            case 'GetAllNotes':
                $notes = PresetNote::getAllNotes();

                $this->ajaxDie(json_encode([
                    'status' => true,
                    'notes' => $notes,
                ]));
        }
    }
}