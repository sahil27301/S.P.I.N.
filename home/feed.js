// const toggle = document.getElementById("toggle");
const fetchpostsbtn = document.getElementById("fetchpost");
const postsarea = document.getElementById("postsarea");
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
  if (scrollTop + clientHeight >= scrollHeight && more) {
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
  if (!more) {
    return;
  }
  var xhr = new XMLHttpRequest();
  // console.log("hell");
  xhr.open("POST", "fetchposts.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var params = "start=" + start + "&limit=" + limit;
  xhr.onload = function (event) {
    if (this.status == 200) {
      // console.log("facebook");
      // console.log(start);
      // console.log(this.responseText);
      if (this.responseText == "Reached") {
        more = false;
        return;
      }
      output += this.responseText;
      // else if (!searching) {
      // var posts = JSON.parse(this.responseText);
      // searching = true;
      // for (i in posts) {
      //   output +=
      //     '<div class="posts-style">' +
      //     "<ul>" +
      //     "<li>" +
      //     posts[i].post_id +
      //     "</li>" +
      //     "<li>" +
      //     posts[i].user_id +
      //     "</li>" +
      //     "<li>" +
      //     posts[i].caption +
      //     "</li>" +
      //     "</ul>" +
      //     "</div>";
      // }
      postsarea.innerHTML = output;
      // console.log(start);
      // }
    }
  };
  xhr.send(params);
  start += limit;
  // searching = false;
}
// $(".carousel").on("slid.bs.carousel", "", function () {
//   var $this;
//   console.log($(this).children("div.carousel-inner").children("carousel-item"));
//   $this = $(this);
//   if (
//     $(this)
//       .children("div.carousel-inner .carousel-item:first")
//       .hasClass("active")
//   ) {
//     $this.children(".carousel-control-prev").hide();
//     $this.children(".carousel-control-next").show();
//   } else if (
//     $(this)
//       .children("div.carousel-inner .carousel-item:last")
//       .hasClass("active")
//   ) {
//     $this.children(".carousel-control-prev").hide();
//     $this.children(".carousel-control-next").show();
//   } else {
//     $this.children(".carousel-control").show();
//   }
// });
//
//
//
// $(document).ready(function () {
//   $("#sidebar").mCustomScrollbar({
//     theme: "minimal",
//   });

//   $("#sidebarCollapse").on("click", function () {
//     // open or close navbar
//     $("#sidebar").toggleClass("active");
//     // $(".wrapper").toggleClass("active");
//     // close dropdowns
//     $(".collapse.in").toggleClass("in");
//     // and also adjust aria-expanded attributes we use for the open/closed arrows
//     // in our CSS
//     $("a[aria-expanded=true]").attr("aria-expanded", "false");
//   });
// });
