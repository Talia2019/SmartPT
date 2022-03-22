$(function() {
  $('.navIcons').click(function() {
    $(this).children('.navIcon').css('color', '#8DA5BD');
    $(this).children('.navName').css('color', '#8DA5BD');
  }, function() {
    // on mouseout, reset the background colour
    $(this).children('.navIcon').css('color', '#BDBDBD');
    $(this).children('.navName').css('color', '#BDBDBD');
  });
});
