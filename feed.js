const toggle = document.getElementById("toggle");
const fetchpostsbtn = document.getElementById("fetchpost");
const postsarea = document.getElementById("postsarea");
const loading = document.getElementById("loader");
var start = 0;
var limit = 3;
var searching = false;
var output = "";
toggle.addEventListener("click", () => {
  document.body.classList.toggle("show-nav");
});
window.addEventListener("scroll", function () {
  const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
  if (scrollTop + clientHeight >= scrollHeight - 5) {
    loading.classList.add("show");
    setTimeout(() => {
      loading.classList.remove("show");
    }, 2000);
    setTimeout(() => {
      fetchpost();
    }, 2000);
  }
});
fetchpostsbtn.addEventListener("click", fetchpost);

function fetchpost() {
  var xhr = new XMLHttpRequest();
  console.log("hell");
  xhr.open("POST", "fetchposts.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var params = "start=" + start + "&limit=" + limit;
  xhr.onload = function () {
    if (this.status == 200) {
      console.log("facebook");
      console.log(start);
      console.log(this.responseText);
      if (this.responseText == "Reached") return;
      else if (!searching) {
        var posts = JSON.parse(this.responseText);
        searching = true;
        for (i in posts) {
          output +=
            '<div class="posts-style">' +
            "<ul>" +
            "<li>" +
            posts[i].post_id +
            "</li>" +
            "<li>" +
            posts[i].user_id +
            "</li>" +
            "<li>" +
            posts[i].caption +
            "</li>" +
            "</ul>" +
            "</div>";
        }
        postsarea.innerHTML = output;
        start += limit;
      }
    }
  };
  xhr.send(params);
  searching = false;
}
