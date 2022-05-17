document.addEventListener('DOMContentLoaded', (e) => {
    let note_input = document.querySelector('.note_content');

    note_input.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && (e.keyCode == 13 || e.keyCode == 10)) {
            document.querySelector('#genki_preset_note_form').submit();
        }
    })
})