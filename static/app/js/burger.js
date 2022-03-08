const burgerMenu = document.querySelector('.burger-menu');
const menuBody = document.querySelector('.menu__body');
const menuList = document.querySelectorAll('.menu__item');
const body = document.body;
if (burgerMenu) {
  burgerMenu.addEventListener('click', function (e) {
    burgerMenu.classList.toggle('active');
    menuBody.classList.toggle('active');
    body.classList.toggle('lock');
    if (burgerMenu.classList.contains('active')) {
      document.addEventListener('click', function ({ target }) {
        if (!target.closest('.header__body')) {
          burgerMenu.classList.remove('active');
          menuBody.classList.remove('active');
          menuList.forEach((element) => {
            element.classList.remove('active');
          });
          body.classList.remove('lock');
        }
      });
    }
  });
}
