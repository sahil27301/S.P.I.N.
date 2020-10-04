const toggle = document.getElementById("toggle");
const fetchpostsbtn = document.getElementById("fetchpost");
const postsarea = document.getElementById("postsarea");
const loading = document.getElementById("loader");
var start = 0;
var limit = 3;
// var searching = false;
var more = true;
var output = "";
toggle.addEventListener("click", () => {
  document.body.classList.toggle("show-nav");
});
window.addEventListener("scroll", function () {
  const { scrollTop, scrollHeight, clientHeight } = document.documentElement;
  if (scrollTop + clientHeight >= scrollHeight - 5 && more) {
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

function fetchpost() {
  if (!more) {
    return;
  }
  var xhr = new XMLHttpRequest();
  // console.log("hell");
  xhr.open("POST", "fetchposts.php", true);
  xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  var params = "start=" + start + "&limit=" + limit;
  xhr.onload = function () {
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
      start += limit;
      // }
    }
  };
  xhr.send(params);
  // searching = false;
}

// execute function after sliding:
// $(".carousel").on("slid.bs.carousel", function () {
//   console.log("happened");
//   // This variable contains all kinds of data and methods related to the carousel
//   var carouselData = $(this).data("bs.carousel");
//   // get current index of active element
//   var currentIndex = carouselData.getItemIndex(
//     carouselData.$element.find(".item.active")
//   );

//   // hide carousel controls at begin and end of slides
//   $(this).children(".carousel-control").show();
//   if (currentIndex == 0) {
//     $(this).children(".carousel-control-prev").hide();
//   } else if (currentIndex + 1 == carouselData.$items.length) {
//     $(this).children(".carousel-control-next").hide();
//   }
// });
