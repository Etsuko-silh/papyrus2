document.addEventListener("DOMContentLoaded", function () {
    const categoryButtons = document.querySelectorAll(".nav_item");
    const books = document.querySelectorAll(".book-card");

    categoryButtons.forEach(button => {
        button.addEventListener("click", function () {
            const category = this.getAttribute("data-category");

            books.forEach(book => {
                if (category === "all" || book.getAttribute("data-category") === category) {
                    book.style.display = "flex";
                } else {
                    book.style.display = "none";
                }
            });

            categoryButtons.forEach(btn => btn.classList.remove("active"));
            this.classList.add("active");

            // Автообновление поиска при смене категории
            document.getElementById("search").dispatchEvent(new Event("input"));
        });
    });
});
