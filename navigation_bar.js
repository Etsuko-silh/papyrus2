document.addEventListener("DOMContentLoaded", function () {
    const navItems = document.querySelectorAll(".nav_item");
    const navIndicator = document.querySelector(".nav_indicator");

    if (!navItems.length || !navIndicator) {
        console.error("Элементы навигации не найдены!");
        return;
    }

    const indicatorWidth = 100; // Фиксированная ширина индикатора
    navIndicator.style.width = `${indicatorWidth}px`;

    function moveIndicator(activeItem) {
        const parentButton = activeItem.closest("button");
        if (!parentButton) return;

        const buttonCenter = parentButton.offsetLeft + parentButton.offsetWidth / 2;
        const indicatorPosition = buttonCenter - indicatorWidth / 2;

        navIndicator.style.transform = `translateX(${indicatorPosition}px)`;
    }

    // Устанавливаем начальную позицию индикатора на активный элемент
    const activeItem = document.querySelector(".nav_item.active") || navItems[0];
    if (activeItem) {
        moveIndicator(activeItem);
    }

    navItems.forEach((item) => {
        item.addEventListener("click", function () {
            navItems.forEach((el) => el.classList.remove("active"));
            this.classList.add("active");
            moveIndicator(this);
        });
    });
});


