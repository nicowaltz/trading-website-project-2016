(function($) {
  "use strict";
  var buy = function() {
    var $button = $('#lookup'),
        $outputField = $('#res'),
        $inputField = $('#stock');

    function sendAjaxReq() {
      $.ajax({
        url: '/lookup',
        cache: false,
        data: {
          "s": $inputField.val() 
        },
        success: function(res) {
          $outputField.html(res);
        },
        error: function(xhr) {
          $outputField.html(null);
        }
      });
    }


    // Listen
    $button.on('click', sendAjaxReq);

  };

  var portfolioPopUp = function() {

    var $tr = $('.portfolio-table tbody tr');

    function showPopUp(e) {
      var $this = $(e.target).closest('tr'),
          $popUp = $this.find('.d .details'),
          $siblingsPopUp = $this.siblings().find('.d .details');


      $popUp.toggleClass('show');
      $siblingsPopUp.toggleClass('show');


    }

    // Listen
    $tr.on('click', showPopUp);

  };

  // instanciate
  $(document).ready(function() {
    buy();
    portfolioPopUp();
  });

})( jQuery );
