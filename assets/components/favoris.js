import axios from "axios";

console.log('test');
import $ from "jquery"

function onClickBtnFavorites(event) {
    event.preventDefault();

    const url = this.href;
    const icon = this.querySelector('i');
    axios.get(url).then(function (response) {
        //console.log(this);
    })
}

document.querySelectorAll("a.js-annonce").forEach(function (favoris) {
    favoris.addEventListener('click', onClickBtnFavorites);
})
