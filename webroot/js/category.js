let viewCategory = {
  setupMap: function() {
    $('#county-paths path').each(function() {
      // Set up popup info for county
      $(this).qtip({
        content: $('#' + this.id + '-details').remove(),
        solo: true,
        effect: false,
        show: {delay: 0, effect: false},
        hide: {delay: 0, effect: false},
        position: {
          my: 'bottom center',
          at: 'top center',
          target: 'mouse',
          adjust: {
            y: -20,
            mouse: true
          }
        }
      });

      // Make county clickable
      this.addEventListener('click', function() {
        const countySlug = this.id.replace('in-map-', '');
        window.location.href = '/county/' + countySlug;
      });
    });

    // Set up buttons
    $('#show-download-options').click(function(event) {
      event.preventDefault();
      $('#download-options-wrapper').slideToggle(500);
    });
    $('#show-map').click(function (event) {
      event.preventDefault();
      viewCategory.selectCategoryMode('map');
    });
    $('#show-table').click(function (event) {
      event.preventDefault();
      viewCategory.selectCategoryMode('table');
    });
  },

  selectCategoryMode: function(mode) {
    let report = $('#category-report');
    let map = $('#show-map');
    let table = $('#show-table');
    if (mode === 'map') {
      report.find('.map-wrapper').first().show();
      map.addClass('selected');
      report.find('.table-wrapper').first().hide();
      table.removeClass('selected');
    } else if (mode === 'table') {
      report.find('.map-wrapper').first().hide();
      map.removeClass('selected');
      report.find('.table-wrapper').first().show();
      table.addClass('selected');
    }
  }
};
