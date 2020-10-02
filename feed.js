const toggle = document.getElementById("toggle");
const fetchpostsbtn = document.getElementById("fetchpost");
toggle.addEventListener("click", () => {
  document.body.classList.toggle("show-nav");
});
fetchpostsbtn.addEventListener("click", () => {
  var xhr = new XMLHttpRequest();
});
