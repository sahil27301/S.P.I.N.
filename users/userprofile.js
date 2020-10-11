// const toggle = document.getElementById("toggle");
const fetchpostsbtn = document.getElementById("fetchpost");
const postsarea = document.getElementById("mypostsarea");
const loading = document.getElementById("loader");
const postfocus = document.getElementsByClassName("posts-style");
var start = 0;
var limit = 3;
// var searching = false;
var more = true;
var output = "";
// toggle.addEventListener("click", () => {
//   document.body.classList.toggle("show-nav");
// });
window.addEventListener("scroll", function () {
  var { scrollTop, scrollHeight, clientHeight } = document.documentElement;
  if (scrollTop + clientHeight + 10 >= scrollHeight && more) {
    loading.classList.add("show");
    setTimeout(() => {
      loading.classList.remove("show");
    }, 1000);
    setTimeout(() => {
      fetchpost();
    }, 1000);
  }
});
//Fire first query on load
$(document).ready(fetchpost);

function fetchpost(event) {
  // console.log(start);
  if (!more) {
    return;
  }
  var xhr = new XMLHttpRequest();
  // console.log("hell");
  xhr.open("POST", "fetchuserprofile.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var params =
    "start=" +
    start +
    "&limit=" +
    limit +
    "&user_id=" +
    findGetParameter("user_profile");
  xhr.onload = function (event) {
    if (this.status == 200) {
      // console.log("facebook");
      // console.log(start);
      // console.log(this.responseText);
      if (this.responseText == "Reached") {
        more = false;
        return;
      }
      output = this.responseText;

      postsarea.innerHTML += output;
    }
  };
  xhr.send(params);
  start += limit;
}

function findGetParameter(parameterName) {
  var result = null,
    tmp = [];
  var items = location.search.substr(1).split("&");
  for (var index = 0; index < items.length; index++) {
    tmp = items[index].split("=");
    if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
  }
  return result;
}
