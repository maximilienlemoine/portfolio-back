import TomSelect from 'tom-select';

export function initializeSelects() {
    // Get all select elements with the class 'select-mono'
    const monoSelects = document.querySelectorAll('.select-mono');
    monoSelects.forEach(select => {
        new TomSelect(select, {
            plugins: ['remove_button'],
            persist: false,
            maxItems: 1
        });
    });

    // Get all select elements with the class 'select-multi'
    const multiSelects = document.querySelectorAll('.select-multi');
    multiSelects.forEach(select => {
        new TomSelect(select, {
            plugins: ['remove_button'],
            persist: false,
            maxItems: null
        });
    });
}