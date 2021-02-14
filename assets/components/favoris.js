console.log('test');
import $ from "jquery"

function onClickBtnFavorites(event) {
    event.preventDefault();

    const url = this.href;

}

document.querySelectorAll("a.js-annonce").forEach(function (favoris) {
    favoris.addEventListener('click', onClickBtnFavorites);
})
