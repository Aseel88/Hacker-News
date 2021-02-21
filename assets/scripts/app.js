// "use strict";

// const likeBtns = document.querySelectorAll("button.like");

// likeBtns.forEach((likeBtn) => {
//   likeBtn.addEventListener("click", (e) => {
//     // const url = e.currentTarget.dataset.url;
//     // ?id=${url}
//     fetch("../like.php", {
//       credentials: "include",
//       method: "POST",
//     })
//       .then(function (response) {
//         return response.json();
//       })
//       .then((data) => {
//         data.json();
//       });
//   });
// });

const sidenav = document.getElementById("side-menu");

const hamburger = document.querySelector(".fas");

const closemenu = document.querySelector(".btn-close");

hamburger.addEventListener("click", function () {
  sidenav.style.width = 100 + "px";
});

closemenu.addEventListener("click", function () {
  sidenav.style.width = 0 + "px";
});
