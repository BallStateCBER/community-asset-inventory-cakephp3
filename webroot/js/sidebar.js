function setupSidebar() {
  $('#select-county-button').click(function(event) {
    event.preventDefault();
    const countySlug = $('#select-county').find('option:selected').val();
    if (countySlug) {
      window.location.href = '/county/' + countySlug;
    }
  });
}
