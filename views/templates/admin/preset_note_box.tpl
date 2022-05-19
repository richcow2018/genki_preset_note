{*
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
*}

<div class="card mt-2">
    <div class="card-header">
        <div class="row">
            <div class="col-md-6">
                <h3 class="card-header-title">
                    {l s='Order Notes' mod='genki_preset_note'}
                </h3>
            </div>
        </div>
    </div>

    <div class="card-body">
        <form method="post" action="{$form_action}" name="genki_order_note" id="genki_order_note">
            <div class="form-group row mb-0">
                <label for="preset_notes_choice" class="form-control-label label-on-top col-12">
                    {l s='Choose from preset notes' mod='genki_preset_note'}
                </label>
                <div class="col-12">
                    <select id="preset_notes_choice" name="preset_notes_choice" class="custom-select">
                        {if isset($notes)}
                            <option value=""></option>
                            {foreach $notes as $key => $note}
                                <option value="{$key}">{$note['note']}</option>
                            {/foreach}
                        {/if}
                    </select>
                </div>
                <input type="hidden" name="id_order" value="{$id_order}">
            </div>
            <div class="form-group row configure">
                <div class="col-sm">
                    <a href="{$note_config}" class="configure-link">
                        {l s='Configure preset notes' mod='genki_preset_note'}
                        <i class="material-icons">arrow_right_alt</i>
                    </a>
                </div>
            </div>

            <div class="form-group row type-text_with_length_counter js-text-with-length-counter">
                <label for="preset_notes_content" class="form-control-label label-on-top col-12">
                    <span class="text-danger">*</span>
                    {l s='Note' mod='genki_preset_note'}
                </label>
                <div class="col-12">
                    <div class="input-group js-text-with-length-counter">
                        <textarea id="preset_notes_content" name="order_note" required="required" cols="30" rows="3" class="js-countable-input form-control" data-max-length="1200" maxlength="1200">{$note_content}</textarea>
                        <div class="input-group-append">
                            <span class="input-group-text js-countable-text">1200</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-right">
                <button type="submit" class="btn btn-primary preset_note_submit">{l s='Save' mod='genki_preset_note'}</button>
            </div>
        </form>
    </div>
</div>
