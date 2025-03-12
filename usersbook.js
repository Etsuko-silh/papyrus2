document.addEventListener('DOMContentLoaded', () => {
    const openModal = document.getElementById("openModal");
    const modal = document.getElementById("modal");
    const closeModal = document.getElementById("closeModal");

    // Проверка существования элементов
    if (!openModal || !modal || !closeModal) {
        console.error('Один из элементов модального окна не найден!');
        return;
    }

    // Открытие модалки
    openModal.addEventListener("click", () => {
        modal.style.display = "flex"; // Активируем flex-контейнер
    });

    // Закрытие модалки
    const closeModalFunc = () => {
        modal.style.display = "none";
    };

    // Закрытие по крестику
    closeModal.addEventListener("click", closeModalFunc);

    // Закрытие по клику вне контента
    modal.addEventListener("click", (e) => {
        if (e.target === modal) { // Клик именно на фоне
            closeModalFunc();
        }
    });

    // Закрытие по ESC
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            closeModalFunc();
        }
    });
});