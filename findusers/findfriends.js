$(function () {
  $("#searchbox").autocomplete({
    source: "/spin/findusers/findfriendsbackend.php",
    change: function (event, ui) {
      if (ui.item == null) {
        //here is null if entered value is not match in suggestion list
        $(this).val(ui.item ? ui.item.id : "");
      }
    },
  });
});
