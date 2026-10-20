document.addEventListener('DOMContentLoaded', function () {
  const sidebar = document.getElementById('sidebar');
  const sidebarToggle = document.getElementById('sidebarToggle');

  if (sidebarToggle && sidebar) {
    sidebarToggle.addEventListener('click', function () {
      sidebar.classList.toggle('collapsed');
    });
  }

  const subnavLinks = document.querySelectorAll('.subnav-link');
  subnavLinks.forEach(link => {
    link.addEventListener('click', function (e) {
      e.preventDefault();
      subnavLinks.forEach(l => l.classList.remove('active'));
      this.classList.add('active');
    });
  });

  const genreChips = document.querySelectorAll('.genre-chip');
  const bookCards = document.querySelectorAll('.book-card');

  genreChips.forEach(chip => {
    chip.addEventListener('click', function () {
      const selectedGenre = this.getAttribute('data-filter');

      genreChips.forEach(c => c.classList.remove('active'));
      this.classList.add('active');

      bookCards.forEach(card => {
        const cardGenre = card.getAttribute('data-genre');

        if (selectedGenre === 'all' || cardGenre === selectedGenre) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  });

  const searchInputs = document.querySelectorAll('.search-box input');
  searchInputs.forEach(input => {
    input.addEventListener('input', function () {
      const query = this.value.toLowerCase().trim();

      document.querySelectorAll('.book-card, .book-item').forEach(card => {
        const titleEl = card.querySelector('.book-title');
        const authorEl = card.querySelector('.book-author');

        if (!titleEl) return;

        const title = titleEl.textContent.toLowerCase();
        const author = authorEl ? authorEl.textContent.toLowerCase() : '';
        const genreEl = card.querySelector('.genre-tag');
        const genre = genreEl ? genreEl.textContent.toLowerCase() : '';
        const cardGenre = card.getAttribute('data-genre');
        const genreText = (genreEl ? genreEl.textContent : '') + (cardGenre ? ' ' + cardGenre : '');

        const match = title.includes(query) || author.includes(query) || genreText.toLowerCase().includes(query);
        card.style.display = match ? 'block' : 'none';
      });
    });
  });
});