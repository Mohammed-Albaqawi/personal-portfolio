const root = document.documentElement;
const languageButton = document.querySelector('#language-toggle');
const themeButton = document.querySelector('#theme-toggle');
const menuButton = document.querySelector('#menu-toggle');
const navLinks = document.querySelector('#nav-links');

function setLanguage(language) {
  root.lang = language;
  root.dir = language === 'ar' ? 'rtl' : 'ltr';
  root.dataset.language = language;
  languageButton.textContent = language === 'ar' ? 'EN' : 'عربي';
  localStorage.setItem('portfolio-language', language);
}

function setTheme(theme) {
  root.dataset.theme = theme;
  localStorage.setItem('portfolio-theme', theme);
}

languageButton.addEventListener('click', () => {
  setLanguage(root.lang === 'en' ? 'ar' : 'en');
});

themeButton.addEventListener('click', () => {
  setTheme(root.dataset.theme === 'dark' ? 'light' : 'dark');
});

menuButton.addEventListener('click', () => {
  const open = menuButton.getAttribute('aria-expanded') !== 'true';
  menuButton.setAttribute('aria-expanded', String(open));
  navLinks.classList.toggle('open', open);
});

navLinks.querySelectorAll('a').forEach((link) => {
  link.addEventListener('click', () => {
    navLinks.classList.remove('open');
    menuButton.setAttribute('aria-expanded', 'false');
  });
});

setLanguage(localStorage.getItem('portfolio-language') || 'en');
setTheme(localStorage.getItem('portfolio-theme') || 'light');
