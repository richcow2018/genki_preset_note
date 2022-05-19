document.addEventListener('DOMContentLoaded', (e) => {
    let note_select = document.querySelector('#preset_notes_choice');
    let note_content = document.querySelector('#preset_notes_content');

    note_select.addEventListener('change', (e) => {
        let choice = note_select.selectedIndex;
        let choice_txt = note_select.options[choice].text;

        if (note_content.value !== '') {
            note_content.value += '\r\n';
        }
        note_content.value += choice_txt;
        note_select.selectedIndex = 0;
    });

    note_content.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && (e.keyCode == 13 || e.keyCode == 10)) {
            document.querySelector('#genki_order_note').submit();
        }
    })
});