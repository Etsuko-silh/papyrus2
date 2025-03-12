document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById('search');
    const resultsContainer = document.getElementById('results');
    const searchPopup = document.getElementById('search-popup');
    let timeoutId;

    function debounce(func, delay = 300) {
        return (...args) => {
            clearTimeout(timeoutId);
            timeoutId = setTimeout(() => func.apply(this, args), delay);
        };
    }

    async function fetchResults(query) {
        try {
            const response = await fetch(`search.php?q=${encodeURIComponent(query)}`);
            return await response.json();
        } catch (error) {
            console.error('Search error:', error);
            return [];
        }
    }

    function displayResults(results) {
        resultsContainer.innerHTML = '';
        if (results.length === 0) {
            resultsContainer.innerHTML = '<li class="no-results">No books found</li>';
            return;
        }

        results.forEach(book => {
            const li = document.createElement('li');
            li.textContent = book.title;
            li.addEventListener('click', () => {
                searchInput.value = book.title;
                applyFilters();
                searchPopup.style.display = 'none';
            });
            resultsContainer.appendChild(li);
        });
    }

    function applyFilters() {
        const activeCategory = document.querySelector('.nav_item.active').dataset.category;
        const searchQuery = searchInput.value.trim().toLowerCase();
        
        document.querySelectorAll('.book-card').forEach(card => {
            const category = card.dataset.category;
            const title = card.querySelector('h3').textContent.toLowerCase();
            
            const categoryMatch = activeCategory === 'all' || category === activeCategory;
            const searchMatch = title.includes(searchQuery);
            
            card.style.display = categoryMatch && searchMatch ? 'flex' : 'none';
        });
    }

    searchInput.addEventListener('input', debounce(async (e) => {
        const query = e.target.value.trim();
        
        if (!query) {
            applyFilters();
            searchPopup.style.display = 'none';
            return;
        }

        const results = await fetchResults(query);
        searchPopup.style.display = 'block';
        displayResults(results);
    }));

    searchInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            applyFilters();
            searchPopup.style.display = 'none';
        }
    });

    document.addEventListener('click', (e) => {
        if (!searchPopup.contains(e.target) && e.target !== searchInput) {
            searchPopup.style.display = 'none';
        }
    });
});