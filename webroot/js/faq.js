function setupFaq() {
  $('#faq-showtable-fig1').click(function(event) {
    event.preventDefault();
    $('#faq-fig1-img').hide();
    $('#faq-fig1-table').show();
  });
  $('#faq-showimg-fig1').click(function(event) {
    event.preventDefault();
    $('#faq-fig1-img').show();
    $('#faq-fig1-table').hide();
  });
  $('#faq-showtable-fig2').click(function(event) {
    event.preventDefault();
    $('#faq-fig2-img').hide();
    $('#faq-fig2-table').show();
  });
  $('#faq-showimg-fig2').click(function(event) {
    event.preventDefault();
    $('#faq-fig2-img').show();
    $('#faq-fig2-table').hide();
  });
}
