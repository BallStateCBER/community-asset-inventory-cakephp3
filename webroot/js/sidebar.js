function removeCategoryHighlight() {
  $('#categories').find('.selected').removeClass('selected');
}

function showFullReport(county) {
  // Select the same county in the dropdown menu and remove blank options
  let options = $('#select_county option');
  let len = options.length;
  for (let i = 0; i < len; i++) {
    if (options[i].value === county) {
      options[i].selected = true;
    }
  }

  // Remove blank options
  for (let i = 0; i < len; i++) {
    if (options[i].value === '') {
      $(options[i]).remove();
    }
  }

  removeCategoryHighlight();

  if (! county) {
    county = $('#select_county').val();
  }
  window.location.hash = county + '_county';

  // Display the full report
  $('#content').load('/reports/county/' + county);
}

function showPage(page) {
  removeCategoryHighlight();
  $('#content').load('/pages/' + page);
}

function setupSidebar() {
  $('#select-county-button').click(function(event) {
    event.preventDefault();
    const countySlug = $('#select-county').find('option:selected').val();
    if (countySlug) {
      window.location.href = '/county/' + countySlug;
    }
  });
  $('#sources-link').click(function() {
    showPage('sources');
  });
  $('#faq-link').click(function() {
    showPage('faq');
  });
  $('#credits-link').click(function() {
    showPage('credits');
  });
  $('#home-link').click(function() {
    showPage('home');
  });
}

function showMap(slug) {
  removeCategoryHighlight();
  $('#showmap_' + slug).closest('li').addClass('selected');
  $('#content').load('/reports/category/' + slug);
}

function setupShowMap(categorySlugs) {
  for (let n = 0; n < categorySlugs.length; n++) {
    const slug = categorySlugs[n];
    $('#showmap-' + slug).click(function() {
      showMap(slug);
    });
  }
}

function processHash(categories, counties) {
  if (!window.location.hash) {
    return;
  }

  let hash = window.location.hash.substring(1);
  if (categories.indexOf(hash) !== -1) {
    return showMap(hash);
  }

  if (hash.indexOf('_county') !== -1) {
    let countyName = hash.replace('_county', '');
    if (counties.indexOf(countyName) !== -1) {
      return showFullReport(countyName);
    }
  }

  switch (hash) {
    case 'sources':
    case 'faq':
    case 'credits':
    case 'home':
      showPage(hash);
      break;
    default:
      showMap('People');
  }
}
