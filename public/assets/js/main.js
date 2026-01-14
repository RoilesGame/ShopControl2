document.addEventListener('DOMContentLoaded', () => {
    const tableBtn = document.querySelector('[data-view="table"]');
    const gridBtn = document.querySelector('[data-view="grid"]');
    const tableView = document.getElementById('tableView');
    const gridView = document.getElementById('gridView');

    if (tableBtn && gridBtn && tableView && gridView) {
        const switchView = (view) => {
            if (view === 'table') {
                tableView.classList.remove('hidden');
                gridView.classList.add('hidden');
            } else {
                gridView.classList.remove('hidden');
                tableView.classList.add('hidden');
            }
        };

        tableBtn.addEventListener('click', () => switchView('table'));
        gridBtn.addEventListener('click', () => switchView('grid'));
    }

    const slider = document.querySelector('[data-slider]');
    if (slider) {
        const slides = Array.from(slider.querySelectorAll('.slide'));
        if (slides.length > 0) {
            let index = 0;
            slides[index].classList.add('active');

            setInterval(() => {
                slides[index].classList.remove('active');
                index = (index + 1) % slides.length;
                slides[index].classList.add('active');
            }, 4000);
        }
    }
});
