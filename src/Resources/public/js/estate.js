/*
 * list view scripts from makler modul for contao cms
 * module website https://www.maklermodul.de
 * for documentation visit https://docs.maklermodul.de
 */

(function (window, document, $, undefined) {
  'use strict';
  window.listView = {};

  var $EstateList = {};

  listView.init = function () {
    // get it started
    listView.cacheSelectors();

    // object counter
    listView.estateListHeadline.prepend("<span id='objCnt' class='color-accent'>" + listView.objCnt + "</span>&nbsp;");
    listView.estateListCnt = $('#objCnt');

    listView.renderRangeSliders();

    // load filters from hash
    listView.getHashFilter();

    // load estates
    listView.getEstates();

    // register events
    listView.body
        .on('click', '.mm-reset', listView.reset) // bind reset buttons
        .on('click', '.mm-shuffle', listView.shuffle) // bind shuffle buttons
        .on('click', '.mm-filter-ckb', listView.filterByCheckbox) // bind checkboxes
        .on('click', '.mm-filter-btn', listView.filterByButton) // bind buttons
        .on('click', '.pagination li a.link', listView.filterByPage) // bind pagination links
        .on('change', '.mm-filter-sel', listView.filterBySelect) // bind select fields
        .on('change', '.mm-only-filter .mm-filter-sel', listView.appendFilter) // append filter settings to url (filter only for selects)
        .on('click', '.mm-only-filter .mm-filter-btn', listView.appendFilter) // append filter settings to url (filter only for buttons)
        .on('slideStop', '.mm-filter-range', listView.filterByRange) // bind range sliders
        .on('click', '.anfrage-btn', listView.showObjectRequest)
        .on('keyup', '.mm-quicksearch', listView.quicksearch)
        .on('input', '.mm-nearby', listView.nearby) // bind nearby input
        .on('click', '.mod_immoListView .pagination a', listView.registerScrollTopPagination)
    ;

    // for dev listView.body.on( 'click', '.mm-user-position', listView.setUserPosition ) // bind user position buttons
    // use user position for nearby on secure connection / hide for dev only
    if (location.protocol === 'https:') {
      // listView.body.on( 'click', '.mm-user-position', listView.userPosition ) // bind user position buttons
    } else {
      $('.mm-user-position').hide();
    }
    /* @todo add quick search
             $('.mm-quicksearch').keyup( listView.debounce( function() {
             listView.qsSelector = $(this).attr('data-filter');
             listView.qsRegex = new RegExp( $(this).val(), 'gi' );
             listView.render();
             }, 200 ) );
    */
  };

  listView.quicksearch = function () {
    var $this = $(this);

    listView.qsSelector = $this.attr('data-filter');
    listView.qsRegex = new RegExp($this.val(), 'gi');
    listView.qsString = $this.val();

    listView.render();
  };

  listView.nearby = function () {
    var $this = $(this);
    listView.nearbyDistance = $this.val();
    if (listView.nearbyDistance < 1)
      return;

    var address = '';

    if (listView.qsString !== 'undefined' && listView.qsString !== 'NaN' && listView.qsString !== '' && listView.qsString) {
      var result = $.grep(postcodeArr, function (e) {
        return e.plz === listView.qsString;
      });
      if (result) {
        address += result[0]['ort'] + ' ';
      }
      address += listView.qsString;
    }
    /* // Wenn auskommentiert, werden Objekte im Zentrum des Ortes dargestellt
     if(listView.selectFilter != 'undefined' && listView.selectFilter != 'NaN' && listView.selectFilter != '' && listView.selectFilter){
     address += ' '+listView.selectFilter.replace('.geo-ort-', '');
     }*/
    address += ', Deutschland';

    if (typeof listView.userPosition !== 'undefined') {
      listView.positionSuccess(false, address);
    } else {
      listView.positionSuccess(false, address);
    }
  };

  listView.setUserPosition = function () {
    var options = {
      enableHighAccuracy: true,
      timeout: 5000,
      maximumAge: 0
    };
    navigator.geolocation.getCurrentPosition(listView.positionSuccess, listView.positionError, options);
  };

  listView.positionSuccess = function (pos, addr) {
    if (addr !== undefined) {
      geocoder.geocode({'address': addr}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {

          var lat = results[0].geometry.location.lat();
          var lng = results[0].geometry.location.lng();

          listView.userPosition = {lat: lat, lng: lng, address: results[0].formatted_address};

          filterMarkersByDistance(listView.nearbyDistance, listView.userPosition);
        } else {
          console.log("1 Geocoder failed due to: " + status);
        }
      });

    } else {

      var crd = pos.coords;

      var lat = parseFloat(position.coords.latitude);
      var lng = parseFloat(position.coords.longitude);

      var latlng = new google.maps.LatLng(lat, lng);
      geocoder.geocode({'latLng': latlng}, function (results, status) {
        if (status === google.maps.GeocoderStatus.OK) {
          if (results[0]) {
            listView.userPosition = {lat: lat, lng: lng, address: results[0].formatted_address};
            filterMarkersByDistance(listView.nearbyDistance, listView.userPosition);
          }
        } else {
          console.log("2 Geocoder failed due to: " + status);
        }
      });
    }
  };

  listView.positionError = function (err) {
    console.warn('ERROR(' + err.code + '): ' + err.message);
  };

  listView.cacheSelectors = function () {
    listView.body = $('body');
    listView.estateList = $('#estate_list');
    listView.estateListHeadline = $('#listenansicht h2:first');
    listView.shuffleButtons = $('.mm-shuffle');
    listView.resetButtons = $('.mm-reset');
    listView.searchFields = $('.mm-quicksearch');
    listView.nearbyField = $('.mm-nearby');
    listView.checkboxes = $('.mm-filter-ckb');
    listView.buttons = $('.mm-filter-btn');
    listView.selects = $('.mm-filter-sel');
    listView.ranges = $('.mm-filter-range');
    listView.pagination = $('.mod_immoListView .pagination');
    listView.activePage = $('#pagPage');

    // global variables from template
    if (typeof countObj !== 'undefined') listView.objCnt = window.countObj;
    if (typeof addListPagination !== 'undefined') listView.paginationStatus = window.addListPagination;
    listView.paginationUseIsotope = window.paginationUseIsotope;

    listView.rangeFilters = listView.setDefaultRangeFilter();

    // map
    listView.map = $('.ce_makler_map');
  };

  listView.setDefaultRangeFilter = function () {
    return (typeof rangeFilters !== 'undefined') ? rangeFilters : {
      'area': {'min': 0, 'max': 50000, 'value': {0: 0, 1: 50000}},
      'price': {'min': 0, 'max': 10, 'value': {0: 0, 1: 10}}
    };
  };

  listView.render = function () {
    // get existing filters from hash
    if (listView.hashFilter !== null) {
      var arrFilter = listView.hashFilter.split(',');
      var arrSelects = [];
      var arrCheckboxes = [];

      $.each(arrFilter, function (index, value) {
        // selects or
        $('#estate_filter_list option[value="' + value + '"]').each(function (i, obj) {
          $(obj).attr('selected', 'selected');
          arrSelects.push(value);
        });

        // selects and
        $('#estate_filter_list option').each(function (i, obj) {
          var val = $(obj).val();
          if (value.indexOf(val) !== -1) {
            $(obj).attr('selected', 'selected');
            arrSelects.push(val);
          }

        });

        if (arrSelects.length > 0) {
          if( listView.hashFilter.indexOf(",") > -1 ) listView.selectFilter = arrSelects.join(","); // or
          else listView.selectFilter = arrSelects.join(""); // and
        }

        // checkboxes & buttons
        $(listView.checkboxes).each(function (i, obj) {
          var val = $(obj).val();
          if (!value.indexOf(val) || value.indexOf(val) !== -1) {
            $(obj).prop('checked', true);
            arrCheckboxes.push(val);
          }
        });

        $(listView.buttons).each(function (i, obj) {
          var val = $(obj).attr("data-filter");

          if ($(obj).hasClass('active') || value.indexOf(val) != -1) {
            $(obj).addClass('active');
            arrCheckboxes.push(val);
          }
        });

        if (arrCheckboxes.length > 0) {
          if( listView.hashFilter.indexOf(",") > -1 ) listView.checkboxFilter = arrCheckboxes.join(","); // or
          else listView.checkboxFilter = arrCheckboxes.join(""); // and
        }
      });
    }

    // use pagination if active and no other filter is set
    listView.noPagination = false;
    if (listView.paginationStatus && listView.paginationUseIsotope &&
        typeof listView.qsRegex === 'undefined' &&
        typeof listView.checkboxFilter === 'undefined' &&
        typeof listView.selectFilter === 'undefined' &&
        typeof listView.paginationFilter === 'undefined' &&
        typeof listView.rangeFilter === 'undefined'
    ) {
        listView.paginationFilter = 'page1';
        listView.pagination.show();
        listView.hashFilter = '.page1';
        listView.setWindowHash();
    }
    else if (typeof listView.paginationStatus === 'undefined' &&
        typeof listView.qsRegex === 'undefined' &&
        typeof listView.checkboxFilter === 'undefined' &&
        typeof listView.selectFilter === 'undefined' &&
        typeof listView.paginationFilter === 'undefined' &&
        typeof listView.rangeFilter === 'undefined') {
      listView.noPagination = true;
    }
    if (typeof listView.qsRegex !== 'undefined' || typeof listView.checkboxFilter !== 'undefined' ||
        typeof listView.selectFilter !== 'undefined' || typeof listView.rangeFilter !== 'undefined')
      delete listView.paginationFilter;

    if (listView.paginationStatus && typeof listView.paginationFilter !== 'undefined' ||
        !listView.paginationUseIsotope && listView.paginationStatus) {
      listView.pagination.show();
    } else {
      listView.pagination.hide();
    }

    /* @todo implement special box
            var specialBox = listView.estateList.find('.special-box');
            specialBox.removeClass('.isotope-hidden')
                .addClass('objektart-bueroflaeche objektart-produktions-und-lagerflaechen' +
                    ' preise-provisionspflichtig-false rampe-ja ausstattung-kran page1');

            $('select#geo-ort option').each(function() {
                specialBox.addClass(this.value.replace('.', ''));
            });
    */

    $EstateList = listView.estateList.isotope({
      itemSelector: '.estate',
      layoutMode: 'fitRows',
      resizable: false,
      getSortData: {
        sorting: function (itemElem) {
          var sortByClass = '.' + sorting.sortBy.replace(".", "-");
          sortByClass = sortByClass.replace(".@", "-");
          var sort = jQuery(itemElem).find(sortByClass).text();
          // fix enpty values
          if (sort === '' && sorting.sortAscending)
            sort = '999999999';
          if (sort === '' && !sorting.sortAscending)
            sort = '0';

          if (sorting.sortType === 'text')
            return sort;
          if (sorting.sortType === 'int')
            return parseInt(sort.replace(/[\(\)]/g, ''));
          if (sorting.sortType === 'float') {
            return parseFloat(sort.replace(/[\(\)]/g, ''));
          }
        }
      },
      sortBy: 'sorting',
      sortAscending: sorting.sortAscending,

      filter: function () {
        var $this = $(this);
        //var searchResult = listView.qsRegex ? $this.find( '.'+listView.qsSelector ).text().match( listView.qsRegex ) : true;
        var checkboxResult = listView.checkboxFilter ? $this.is( listView.checkboxFilter ) : true;
        var selectResult = listView.selectFilter ? $this.is( listView.selectFilter ) : true;

        //var area = $this.attr('data-area');
        //var price = $this.attr('data-price');
        //var isInAreaRange = (listView.rangeFilters['area'].min <= area && listView.rangeFilters['area'].max >= area);
        //var isInPriceRange = (listView.rangeFilters['price'].min <= price && listView.rangeFilters['price'].max >= price);

        if(typeof listView.paginationFilter != 'undefined') {
          return listView.paginationFilter ? $this.hasClass(listView.paginationFilter) : true;
        } else {
          return checkboxResult && selectResult;
        }

        //var specialBox = $this.hasClass ('special-box') ? true: false;
        //return result;
      }
    });

    // update filtered list if one filter is set
    if (typeof listView.qsRegex !== 'undefined' || typeof listView.checkboxFilter !== 'undefined' ||
        typeof listView.selectFilter !== 'undefined') {
      //if(listView.hashFilter != null) {
      listView.updateFilteredList(); listView.updateFilterValues();
      //}
    }

    setTimeout(function () {
      // filter map
      if (typeof listView.map !== 'undefined') {
        if (typeof listView.qsRegex !== 'undefined' || typeof listView.checkboxFilter !== 'undefined' || typeof listView.selectFilter !== 'undefined' || listView.resetFilter === true) {
          if (listView.hashFilter !== '.page1' || listView.resetFilter === true) {
            if (typeof filterMarkersByIsotopeStatus === "function") {
              filterMarkersByIsotopeStatus();
            }
          }
        }
      }
      // move special box
      /* @todo implement special box
                  listView.moveSpecialBox();
      */
    }, 600);
  };

  listView.reset = function (e) {
    e.preventDefault();
    // reset checkboxes
    listView.checkboxes.each(function () {
      $(this).prop("checked", false).show();
    });
    // reset buttons
    listView.buttons.each(function () {
      $(this).removeClass('active').show();
      $(this).prop('disabled', false);
    });
    // reset search
    listView.searchFields.each(function () {
      $(this).val('');
    });

    // reset selects
    listView.selects.each(function () {
      $(this).find('option').each(function () {
        $(this).show();
        $(this).prop('disabled', false);
        // for materialize css themes
        if( listView.selects.filter(':hidden').length > 0 ) listView.selects.filter(':hidden').formSelect();
      });
      $(this).find('option:selected').each(function () {
        $(this).prop("selected", false);
      });
    });

    // reset nearby
    listView.nearbyField.val('');

    // unset filter
    delete listView.checkboxFilter;
    delete listView.qsRegex;
    delete listView.qsSelector;
    delete listView.selectFilter;
    delete listView.qsString;

    listView.pagination.show();

    // reset range
    delete listView.rangeFilter;
    listView.rangeFilters = listView.setDefaultRangeFilter();
    listView.resetRangeSliders();

    listView.updateSession('');

    // reset hash
    delete listView.paginationFilter;
    listView.resetFilter = true;
    listView.filter("*");
  };

  listView.shuffle = function () {
    listView.estateList.isotope('shuffle');
  };

  listView.reload = function () {
    listView.estateList.isotope('reloadItems');
  };

  listView.nearbyFilter = function () {
    // unset pagination filter
    delete listView.paginationFilter;
    listView.hashFilter = '.nearby';

    listView.estateList.find('.special-box').addClass('nearby');
    listView.estateList.isotope({filter: '.nearby'}, function () {
      if (typeof filterMarkersByIsotopeStatus === "function") {
        filterMarkersByIsotopeStatus();
      }
      // listView.estateList.find('.special-box').removeClass('.isotope-hidden');
      // listView.moveSpecialBox();
      listView.changeCounter();
    });

  };

  listView.changeCounter = function () {
    var itemElems = $EstateList.isotope('getFilteredItemElements');
    listView.objCnt = $(itemElems).lenght;

    // get all items for pagination
    if (listView.hashFilter && listView.hashFilter.indexOf('.page') > -1 && !listView.qsString && !listView.rangeFilter
        || listView.hashFilter === null && !listView.qsString && !listView.rangeFilter) {
      listView.objCnt = $('#estate_list .estate').length;
    }

    //if(!listView.estateList.find('.special-box').hasClass('isotope-hidden'))
    //  listView.objCnt--;

    listView.estateListCnt.html(listView.objCnt);
  };

  listView.filterByCheckbox = function (e) {
    var filters = [];

    // unset pagination filter
    delete listView.paginationFilter;

    listView.checkboxes.filter(':checked').each(function () {
      filters.push(this.value);
    });

    if (filterType === 0) {
      filters = filters.join(', '); 	//OR
    } else {
      filters = filters.join('');     //AND
    }

    if (filters === '')
      delete listView.checkboxFilter;
    else
      listView.checkboxFilter = filters;

    // call filter
    listView.filter();
  };

  listView.filterByButton = function (e) {
    var filters = [];

    // unset pagination filter
    delete listView.paginationFilter;

    // toogle active
    if ($(this).hasClass('active')) {
      $(this).removeClass('active')
    } else {
      $(this).addClass('active')
    }

    listView.buttons.filter('.active').each(function () {
      filters.push($(this).attr('data-filter'));
    });

    if (filterType === 0) {
      filters = filters.join(', '); 	//OR
    } else {
      filters = filters.join('');     //AND
    }

    if (filters === '')
      delete listView.checkboxFilter;
    else
      listView.checkboxFilter = filters;

    // call filter
    listView.filter();
  };

  listView.filterBySelect = function () {
    // unset pagination filter
    if($(this).val() == '-1') {
      $(this).find('option').show();
    }

    delete listView.paginationFilter;

    var filters = listView.selects.children('option:selected', this).map(function () {
      var id = $(this).parent('select').attr('id');

      var val = $(this).attr('value');
      if (val === '-1' || typeof val === 'undefined')
        return;

      if (id === 'geo-ort')
        return $(this).attr('value');

      if ($(this).attr('value') !== 'alle' && id !== 'sorting' && id !== 'zimmer' && id !== 'umkreis')
        return $(this).attr('value');

    }).get();

    if (filterType === 0) {
      filters = filters.join(', '); 	//OR
    } else {
      filters = filters.join('');     //AND
    }

    if (filters === '')
      delete listView.selectFilter;
    else
      listView.selectFilter = filters;

    listView.selectFilter = filters;

    // call filter
    listView.filter();
  };

  listView.filter = function (e) {
    var filters = {};

    // set filter for group
    if (typeof listView.checkboxFilter !== 'undefined')
      filters['checkbox'] = listView.checkboxFilter;
    if (typeof listView.selectFilter !== 'undefined')
      filters['select'] = listView.selectFilter;
    if (typeof listView.paginationFilter !== 'undefined') {
      filters['page'] = listView.paginationFilter;
    }

    // combine filters
    var filterValue = listView.concatValues(filters);

    if(filterValue == '') {
      listView.selects.find('option').prop('disabled', false);
      if( listView.selects.filter(':hidden').length > 0 ) listView.selects.filter(':hidden').formSelect();
    }

    if (typeof listView.paginationStatus === 'undefined') {
      delete listView.paginationFilter;
      // set filter to * | show all
      if (filters.length === 0 || filterValue === '' || filterValue === -1)
        filterValue = '*';
    }
    else (listView.paginationStatus && filters.length === 0 || listView.paginationStatus && filterValue === '')
    {
      // if no filter is set, use page1 of pagination
      if (filters.length === 0 || filterValue === '') {
        listView.paginationFilter = 'page1';
        filterValue = '.page1';
      }
    }

    if (typeof listView.paginationFilter === 'undefined') {
      listView.noPagination = true;
      listView.pagination.hide();
    }

    listView.hashFilter = filterValue;

    //listView.estateList.isotope({ filter: listView.hashFilter + ', .special-box' });
    listView.estateList.isotope({filter: listView.hashFilter});

    listView.setWindowHash();

    setTimeout(function () {
      // filter map
      if (typeof listView.map !== 'undefined') {
        if (typeof listView.qsRegex !== 'undefined' || typeof listView.checkboxFilter !== 'undefined' || typeof listView.selectFilter !== 'undefined' || listView.resetFilter === true) {
          if (listView.hashFilter !== '.page1' || listView.resetFilter === true) {
            if (typeof filterMarkersByIsotopeStatus === "function") {
              filterMarkersByIsotopeStatus();
            }
          }
        }
      }
      // move special box
      //listView.moveSpecialBox();
    }, 600);

    // hide filter values with no result
    if (filterType === 1) {
      listView.updateFilterValues();
    }
  };

  // flatten object by concatting values
  listView.concatValues = function (obj) {
    var value = '';
    var checkVal = obj['checkbox'];

    if (typeof obj['select'] === 'undefined' || !obj['select'].includes(',')) {
      for (var prop in obj)
        value += obj[prop];

      return value;
    }

    var selVal = obj['select'].split(',');

    if (selVal.length > 0) {
      var valueArr = [];
      for (var i = 0; i < selVal.length; i++) {
        if (typeof checkVal !== 'undefined') {
          valueArr.push(checkVal + selVal[i]);
        } else {
          valueArr.push(selVal[i]);
        }
      }
      return valueArr.join(',');
    }
  };

  listView.filterByRange = function (slideEvt) {
    var sldmin = +slideEvt.value[0],
        sldmax = +slideEvt.value[1],
        filterGroup = $(this).attr('data-filter-group'),
        currentSelection = sldmin + ' - ' + sldmax;

    $(this).siblings('.filter-label').find('.filter-selection').text(currentSelection);

    listView.rangeFilters[filterGroup] = {
      min: sldmin || 0,
      max: sldmax || 100000
    };

    // activate range filter
    listView.rangeFilter = true;

    // call filter
    listView.render();
    // listView.filter();
  };

  listView.filterByPage = function (e) {
    if (!listView.paginationUseIsotope) // use normal links
      return true;

    e.preventDefault();
    listView.paginationFilter = '.' + $(this).attr('data-filter').substring(1);
    $(".pagination li").removeClass("active");
    $(this).parent().addClass("active");
    // Scroll to top of the list view
    // @todo deactivate via modul $('html,body').animate({ scrollTop: $("#slider .mod_article.last").offset().top - 85 }, 600);

    // update active pagination page
    listView.activePage.html($(this).attr('title'));

    listView.filter();
  };

  listView.appendFilter = function () {
    var filterPage = $(".search-estate").attr("href");
    if (filterPage.indexOf("#") !== -1) {
      var filterPage = filterPage.substr(0, filterPage.indexOf("#"));
    }
    $(".search-estate").attr("href", filterPage + "#filter=" + listView.hashFilter);
  };

  listView.renderRangeSliders = function () {
    listView.ranges.each(function () {
      var filterGroup = $(this).attr('data-filter-group');
      $(this).slider({
        tooltip_split: true,
        min: listView.rangeFilters[filterGroup].min,
        max: listView.rangeFilters[filterGroup].max,
        range: true,
        value: [listView.rangeFilters[filterGroup].value[0], listView.rangeFilters[filterGroup].value[1]]
      });
    });
    // @todo get settings from module
    $("<div> m²</div>").appendTo("#filter .flaeche .tooltip");
    $("<div> €/m²</div>").appendTo("#filter .mietpreis .tooltip");
  };

  listView.resetRangeSliders = function () {
    listView.ranges.each(function () {
      var min = $(this).data('slider').options.min;
      var max = $(this).data('slider').options.max;
      $(this).data('slider').setValue([min, max], true);
    });
  };

  listView.updateFilterValues = function () {
    if(filterType != 0) {
      listView.buttons.filter(':hidden').show();
      listView.selects.filter('option:hidden').show();

      if (listView.hashFilter.indexOf(".page") > -1 || listView.hashFilter == -1 || listView.hashFilter == "*") {
        // reset selects
        listView.selects.children('option').each(function () {
          $(this).prop("checked", false);
        });
        // reset buttons
        listView.buttons.filter('.active').each(function () {
          $(this).removeClass('active');
        });

        return;
      }

      var itemElems = $EstateList.isotope('getFilteredItemElements');
      if (itemElems.length > 0) {
        var $itemElems = $(itemElems);
      } else {
        var $itemElems = $(listView.objEstates)[0]['data'];
      }

      // buttons
      listView.buttons.each(function (index, value) {
        if (itemElems.length > 0) {
          var optionValue = $(value).attr('data-filter');
        } else {
          var optionValue = $(value).attr('data-filter').replace(".", "");
        }

        if (!optionValue) {
          // do not update 'any' buttons
          return;
        }

        if (itemElems.length > 0) {
          var length = $itemElems.filter(optionValue).length;
        } else {
          var length = 0;

          var arrFilter = listView.hashFilter.split('.');
          var foundOption = false;
          $($itemElems).each(function () {
            var cssFilterString = $(this)[0]['css-filter-class-string'];

            var found = true;
            var i = 0;
            $(arrFilter).each(function () {
              var filterClass = $(arrFilter)[i];
              if (cssFilterString.indexOf(filterClass) == -1 && filterClass != "") found = false;
              ++i;
            });
            if (found == true) {
              if (cssFilterString.indexOf(optionValue) > -1) foundOption = true;
            }
          });

          if (foundOption == true) length = 1;
        }

        if (length < 1) {
          $(value).hide().prop('disabled', true);
        } else {
          $(value).show().prop('disabled', false);
        }
        // @todo add button count # $(value).text( '(' + length +')' );
      });

      // selects
      listView.selects.children('option', this).map(function (index, value) {
        if (itemElems.length > 0) {
          var optionValue = $(value).val();
          var length = $itemElems.filter(optionValue).length;
        } else {
          var optionValue = $(value).val().replace(".", "");
          var length = 0;

          var foundOption = false;
          var arrFilter = listView.hashFilter.split('.');
          $($itemElems).each(function () {
            var cssFilterString = $(this)[0]['css-filter-class-string'];

            var found = true;
            var i = 0;
            $(arrFilter).each(function () {
              var filterClass = $(arrFilter)[i];
              if (cssFilterString.indexOf(filterClass) == -1 && filterClass != "") found = false;
              ++i;
            });
            if (found == true) {
              if (cssFilterString.indexOf(optionValue) > -1) foundOption = true;
            }
          });

          if (foundOption == true) length = 1;
        }


        if (length == 0 && optionValue != -1) {
          $(value).hide().prop('disabled', true);
        } else {
          $(value).show().prop('disabled', false);
        }
        if( listView.selects.filter(':hidden').length > 0 ) listView.selects.filter(':hidden').formSelect();
        // @todo add button count # $(value).text( '(' + length +')' );
      });
    }
  };

  // debounce so filtering doesn't happen every millisecond
  listView.debounce = function (fn, threshold) {
    var timeout;
    return function debounced() {
      if (timeout) {
        clearTimeout(timeout);
      }

      function delayed() {
        fn();
        timeout = null;
      }

      timeout = setTimeout(delayed, threshold || 100);
    };
  };

  listView.setWindowHash = function () {
    window.location.hash = 'filter=' + listView.hashFilter;
  };

  listView.setHashFilter = function (str) {
    var filter = '';
    if (typeof listView.selectFilter !== 'undefined') {
      var selRes = listView.selectFilter.split(",");
      selRes.forEach(function (entry) {

        if (typeof listView.checkboxFilter !== 'undefined')
          filter += entry + listView.checkboxFilter;
        else
          filter += entry;
      });
    } else if (listView.checkboxFilter) {
      filter += listView.checkboxFilter;
    }

    if (filter === '')
      filter = '.page1';

    listView.hashFilter = filter;

    listView.estateList.filter(listView.hashFilter);
    listView.changeCounter();
  };

  listView.getHashFilter = function () {
    var hash = window.location.hash;
    // get filter=filterName
    var matches = hash.match(/filter=([^&]+)/i);
    var hashFilter = matches && matches[1];
    listView.hashFilter = hashFilter && decodeURIComponent(hashFilter);
  };

  listView.onHashChange = function () {
    listView.getHashFilter();
    listView.render();
  };

  listView.updateFilteredList = function () {
    var filteredObjects = [];

    var itemElems = $EstateList.isotope('getFilteredItemElements');
    var $itemElems = $(itemElems);

    $itemElems.each(function () {
      var uriident = $(this).attr('uriident');

      if (typeof uriident !== 'undefined') {
        filteredObjects.push(uriident);
      }
    });
    listView.updateSession(filteredObjects);
  };

  listView.updateSession = function (filteredObjects) {
    /*$.post("/system/modules/makler_modul_mplus/assets/session.php", {
        filteredList: filteredObjects
    });*/
  };

  listView.showObjectRequest = function () {
    document.getElementById("obid-value").innerHTML = this.getAttribute("data-obj");
    $('input[name="objektnummer"]').val(this.getAttribute("data-obj"));
  };

  listView.moveSpecialBox = function () {
    // find special box and move to top
    var actElem = listView.estateList.find('.special-box');
    $(actElem).removeClass('isotope-hidden');
    listView.estateList.prepend(actElem.remove())
        .isotope('reloadItems')
        .isotope({sortBy: 'original-order'});

    // move x items before special box
    var elems = listView.estateList.children(':not(.isotope-hidden)');
    elems.each(function (index) {
      if (index > 0 && index < 5) {
        listView.estateList.prepend($(this).remove()).isotope('reloadItems').isotope({sortBy: 'original-order'});
      }
    });

    listView.changeCounter();
  };

  listView.getEstates = function () {
    $.getJSON(sourceIndexUri, function (data) {
      listView.objEstates = data;
      // init Isotope
      listView.render();
    });
  };

  listView.registerScrollTopPagination = function (event) {
    if (!listView.paginationUseIsotope) // use normal links
      return true;

    event.preventDefault();

    var href = '.mod_immoListView';
    $('html, body').animate({
      scrollTop: $(href).offset().top - 10
    }, 'slow');
  };

  $(function(){
    listView.init();
  });

  $(window).on('hashchange', listView.onHashchange);

  // @todo activate for nearby or map
  // var postcodeArr = [];
  // $.getJSON("bundles/pdirmaklermodul/js/plz.json", function (json) {
  //  postcodeArr = json;
  // });

})(window, document, jQuery);
