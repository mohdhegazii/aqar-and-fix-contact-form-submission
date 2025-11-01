
document.addEventListener('DOMContentLoaded', function () {
  const moreLessButton = document.querySelector('.more-less-button');
  if (moreLessButton) {
    moreLessButton.addEventListener('click', function () {
      const moreLinks = document.querySelector('.more-links');
      if (moreLinks.style.display === 'none') {
        moreLinks.style.display = 'block';
        moreLessButton.textContent = footer_toggle_vars.show_less;
      } else {
        moreLinks.style.display = 'none';
        moreLessButton.textContent = footer_toggle_vars.show_more;
      }
    });
  }
});
