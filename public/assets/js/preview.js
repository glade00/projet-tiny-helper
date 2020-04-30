$(function () {
  // Multiple images preview in browser
  var imagesPreview = function (input, placeToInsertImagePreview) {

    $('.gallery').html('');

    if (input.files) {
      var filesAmount = input.files.length;

      for (i = 0; i < filesAmount; i++) {
        var reader = new FileReader();

        reader.onload = function (event) {
          $($.parseHTML('<img>')).attr('src', event.target.result).appendTo(placeToInsertImagePreview).addClass(' m-2');
        }

        reader.readAsDataURL(input.files[i]);
      }
    }

  };

  $('#sites_illustration').on('change', function () {
    imagesPreview(this, 'div.gallery');
  });
});