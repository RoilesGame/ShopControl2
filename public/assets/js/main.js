document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-view]');
    if (!btn) return;

    const view = btn.dataset.view;
    const table = document.getElementById('tableView');
    const grid = document.getElementById('gridView');

    if (view === 'table') {
        table.classList.remove('hidden');
        grid.classList.add('hidden');
    } else {
        grid.classList.remove('hidden');
        table.classList.add('hidden');
    }
});
