"use strict";

function like(forms) {
  forms.forEach((form) => {
    form.addEventListener("submit", (event) => {
      event.preventDefault();

      const formData = new FormData(form);
      if (form.classList.contains("commentForm")) {
        fetch("/app/comments/likes.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((json) => {
            const clikeBtn = event.target.querySelector(".clikeBtn");
            const buttonStatus = json.status;

            if (buttonStatus === true) {
              clikeBtn.style.backgroundColor = "grey";
            } else {
              clikeBtn.style.backgroundColor = "blue";
            }

            const cLikeNumbers = document.querySelectorAll(
              ".numberOfLikesComments"
            );
            cLikeNumbers.forEach((cLikeNumber) => {
              if (clikeBtn.dataset.id === cLikeNumber.dataset.id) {
                cLikeNumber.textContent = json.numberOfLikesComments;
              }
            });
          });
      } else if (form.classList.contains("postForm")) {
        fetch("/app/posts/likes.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((json) => {
            const likeBtn = event.target.querySelector(".likeBtn");
            const buttonStatus = json.status;

            if (buttonStatus === true) {
              likeBtn.style.backgroundColor = "grey";
            } else {
              likeBtn.style.backgroundColor = "blue";
            }

            const LikeNumbers = document.querySelectorAll(".numberOfLikes");
            LikeNumbers.forEach((LikeNumber) => {
              if (likeBtn.dataset.id === LikeNumber.dataset.id) {
                LikeNumber.textContent = json.numberOfLikes;
              }
            });
          });
      }
    });
  });
}

like(document.querySelectorAll(".like.commentForm"));

like(document.querySelectorAll(".like.postForm"));
