// "use strict";

// fetch("../app/functions.php", {
//   method: "POST",
//   body: FormData,
// })
//   .then((response) => response.json())
//   .then((json) => {
//     console.log($pdo);
//   });

// async function getData() {
//   try {
//     const res = await fetch("https://www.breakingbadapi.com/api/characters");
//     const data = await res.json();
//     console.log(data);
//   } catch (e) {
//     console.log("Error:", e.message);
//     document.write("Server down, Try later");
//   }
// }

// getData();

const sidenav = document.getElementById("side-menu");

const hamburger = document.querySelector(".fas");

const closemenu = document.querySelector(".btn-close");

hamburger.addEventListener("click", function () {
  sidenav.style.width = 100 + "px";
});

closemenu.addEventListener("click", function () {
  sidenav.style.width = 0 + "px";
});
