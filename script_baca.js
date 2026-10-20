document.addEventListener('DOMContentLoaded', function () {
  const readerContainer = document.querySelector('.reader-container');
  const progressBarFill = document.querySelector('.progress-bar-fill');
  const progressInfo = document.querySelector('.progress-info');
  const readingProgressFill = document.getElementById('readingProgressFill');
  const chapterProgressEl = document.getElementById('chapterProgress');
  const percentProgressEl = document.getElementById('percentProgress');
  const darkModeBtn = document.querySelector('.tool-btn[title="Mode Gelap"]');
  const fontSizeBtn = document.querySelector('.tool-btn[title="Pengaturan Teks"]');
  const readerContent = document.querySelector('.reader-content');

  let currentFontSize = 16;
  let isDarkMode = false;

  function updateReadingProgress() {
    if (!readerContainer || !progressBarFill) return;

    const scrollTop = window.scrollY || document.documentElement.scrollTop;
    const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
    const progress = docHeight > 0 ? Math.round((scrollTop / docHeight) * 100) : 0;

    if (progressBarFill) {
      progressBarFill.style.width = progress + '%';
    }

    if (readingProgressFill) {
      readingProgressFill.style.width = progress + '%';
    }

    if (chapterProgressEl || percentProgressEl) {
      const currentChapter = Math.min(28, Math.floor((progress / 100) * 28) + 12);
      if (chapterProgressEl) chapterProgressEl.textContent = 'Bab ' + currentChapter + ' dari 28';
      if (percentProgressEl) percentProgressEl.textContent = progress + '%';
    }
  }

  if (darkModeBtn) {
    darkModeBtn.addEventListener('click', function () {
      isDarkMode = !isDarkMode;
      document.body.classList.toggle('dark-mode', isDarkMode);
      darkModeBtn.style.color = isDarkMode ? '#f1c40f' : '#2b2b2b';
    });
  }

  const fontSizes = [14, 16, 18, 20];
  let fontSizeIndex = 1;

  if (fontSizeBtn && readerContent) {
    fontSizeBtn.addEventListener('click', function () {
      fontSizeIndex = (fontSizeIndex + 1) % fontSizes.length;
      currentFontSize = fontSizes[fontSizeIndex];
      readerContent.style.fontSize = currentFontSize + 'px';
    });
  }

  window.addEventListener('scroll', updateReadingProgress);

  updateReadingProgress();
});