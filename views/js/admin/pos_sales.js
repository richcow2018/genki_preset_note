// Prepare Data
const getAllPresetNote = () => {
    return $.ajax({
        type: 'GET',
        url: window.genki_note_form_action,
        async: false
    }).responseText;
}

let preset_notes = JSON.parse(getAllPresetNote());
window.preset_notes = preset_notes.notes;