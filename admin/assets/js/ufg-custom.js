/**
 * @ufg custom js v1.0.0 - MIT License
 */

var filter_image = UFGJS.FiterImage; //129
//console.log( filter_image );

// Gallery Initial load
jQuery(document).ready(function () {
     var $grid = jQuery('.ufg-gallery-' + UFGJS.GalleryId).isotope({
          itemSelector: '.ufg-thumbnail',
          percentPosition: true,
          masonry: {
               columnWidth: '.ufg-grid-sizer'
          },
          stagger: 30,
          transitionDuration: '0.8s',
     });

     function hideGalleryLoader() {
          var $loader = jQuery('.ufg-gallery-' + UFGJS.GalleryId).find('.ufg-gallery-loader');
          if ($loader.length && !$loader.hasClass('ufg-loader-hidden')) {
               $loader.addClass('ufg-loader-hidden').fadeOut(400);
          }
     }

     // layout Isotope after each image loads
     $grid.imagesLoaded().progress(function (instance, image) {
          jQuery(image.img).closest('.ufg-img-wrap').addClass('loaded');
          $grid.isotope('layout');
     }).always(function () {
          setTimeout(function() {
               hideGalleryLoader();
               $grid.isotope('layout');
          }, 300);
     });

     // Safety fallback timer to hide loader if imagesLoaded takes longer
     setTimeout(function() {
          hideGalleryLoader();
          $grid.isotope('layout');
     }, 1200);

     // Ensure layout is refreshed after window is fully loaded (fixes Firefox timing issues)
     jQuery(window).on('load', function () {
          hideGalleryLoader();
          $grid.isotope('layout');
     });

     // Ensure lazy-loaded images below the fold fade in when they load on scroll
     window.addEventListener('load', function(event) {
          if (event.target.tagName === 'IMG' && jQuery(event.target).hasClass('ufg-thumbnail-img')) {
               jQuery(event.target).closest('.ufg-img-wrap').addClass('loaded');
          }
     }, true);

     jQuery('.ufg-thumbnail-img').each(function() {
          if (this.complete) {
               jQuery(this).closest('.ufg-img-wrap').addClass('loaded');
          }
     });

     jQuery("button.filters").on('click', function () {
          jQuery('button.filters').removeClass('active');
          jQuery(this).addClass('active');
          var selected_filter;
          if (this.value == '*') {
               selected_filter = '*';
          } else {
               selected_filter = "." + this.value;
          }
          
          // Update URL hash for deep linking
          if (UFGJS.EnableDeepLinking) {
               if (this.value === '*') {
                    if (window.location.hash.indexOf('ufg-filter=') > -1) {
                         if (history.replaceState) {
                              history.replaceState(null, null, window.location.pathname + window.location.search);
                         } else {
                              window.location.hash = '';
                         }
                    }
               } else {
                    if (history.replaceState) {
                         history.replaceState(null, null, '#' + 'ufg-filter=' + encodeURIComponent(this.value));
                    } else {
                         window.location.hash = 'ufg-filter=' + encodeURIComponent(this.value);
                    }
               }
          }
          
          var searchQuery = jQuery('.ufg-search-input').length > 0 ? jQuery('.ufg-search-input').val().toLowerCase() : '';
          $grid.isotope({
               filter: function() {
                   var $this = jQuery(this);
                   var searchResult = searchQuery ? $this.text().toLowerCase().indexOf(searchQuery) > -1 : true;
                   var categoryResult = selected_filter && selected_filter !== '*' ? $this.is(selected_filter) : true;
                   return searchResult && categoryResult;
               }
          });

          if (UFGJS.LoadMore == 'on') { //166
               // Change Load button text according images within filters
               var CalTotalItemInFilter = 0;
               var CalTotalLoadedItem = 0;
               var targetFilter = jQuery('.ufg-filter-container button.active').map(function () { return jQuery(this).val(); }).get();
               // Load Images according to filter (ALL)
               if (targetFilter == '*') {
                    CalTotalItemInFilter = parseInt(UFGJS.TotalImages); //31
                    CalTotalLoadedItem = CalTotalLoadedItem + jQuery('.ufg-thumbnail').length;
               }
               // Load Images according to filter (Not ALL)
               if (targetFilter != '*') {
                    jQuery(targetFilter).each(function (index, val) {
                         var isLastElement = index == targetFilter.length - 1;
                         if (isLastElement && filter_image && filter_image[val]) {
                              //console.log(filter_image);
                              CalTotalItemInFilter = CalTotalItemInFilter + filter_image[val].length;
                              CalTotalLoadedItem = CalTotalLoadedItem + jQuery('.ufg-gallery-container >' + 'div.' + val).length;
                         }
                    });
               }
               if (CalTotalItemInFilter == CalTotalLoadedItem) {
                    jQuery('#fg-load-btn').html('No More Result').css({ 'opacity': '00.2', 'pointer-events': 'none' });
               } else {
                    jQuery('#fg-load-btn').html(UFGJS.LoadBtnText + ' <i class="fas fa-circle-notch fa-spin"></i>').css({ 'opacity': '1', 'pointer-events': 'auto' }); //169
               }
          }
     });

     // On Click Load More START
     if (UFGJS.LoadMore == 'on') {
          jQuery("#fg-load-btn").on("click", function (e) {
               jQuery(this).addClass('load');
               e.preventDefault();
               var CalTotalItemInFilter = 0;
               var CalTotalLoadedItem = 0;
               var targetFilter = jQuery('.ufg-filter-container button.active').map(function () { return jQuery(this).val(); }).get();
               // Load Images according to filter (ALL)
               if (targetFilter == '*') {
                    CalTotalItemInFilter = parseInt(UFGJS.TotalImages); //31
                    CalTotalLoadedItem = CalTotalLoadedItem + jQuery('.ufg-thumbnail').length;
               }
               // Load Images according to filter (Not ALL)
               if (targetFilter != '*') {
                    jQuery(targetFilter).each(function (index, val) {
                         var isLastElement = index == targetFilter.length - 1;
                         if (isLastElement && filter_image && filter_image[val]) {
                              CalTotalItemInFilter = CalTotalItemInFilter + filter_image[val].length;
                              CalTotalLoadedItem = CalTotalLoadedItem + jQuery('.ufg-gallery-container >' + 'div.' + val).length;
                         }
                    });
               }

               var ufg_limit_start = CalTotalLoadedItem;
               var ufg_limit_end = parseInt(ufg_limit_start) + parseInt(UFGJS.LoadLimit); // 126 gallery.php

               // Get all loaded items
               var get_all_items = jQuery('.count_attached').map(function () { return jQuery(this).val(); }).get();
               // Check images limit
               if (CalTotalItemInFilter > CalTotalLoadedItem) {
                    jQuery.ajax({
                         dataType: 'html',
                         type: 'POST',
                         url: location.href,
                         cache: false,
                         data: '&ufg_security=' + UFGJS.LoadMoreNonce + '&ufg_limit_start=' + ufg_limit_start + '&ufg_limit_end=' + ufg_limit_end + '&targetFilter=' + targetFilter + '&CalTotalLoadedItem=' + CalTotalLoadedItem + '&get_all_items=' + get_all_items,
                         complete: function () { },
                         success: function (response) {

                              $node = jQuery(response).find('.ufg_result');
                              if ($node.length > 0) {
                                   $grid.append($node).isotope('insert', $node);
                                    $node.imagesLoaded().progress(function (instance, image) {
                                         jQuery(image.img).closest('.ufg-img-wrap').addClass('loaded');
                                         $grid.isotope('layout');
                                    }).always(function () {
                                         $grid.isotope('layout');
                                    });
                              } else {
                                   jQuery('#fg-load-btn').text('No More Result').css({ 'opacity': '00.2', 'pointer-events': 'none' });
                              }
                              setTimeout(function () {
                                   jQuery('#fg-load-btn').removeClass('load');
                              }, 800);

                              jQuery('.ufg-thumbnail').removeClass("ufg_result");
                         },
                         error: function () {
                              jQuery('#fg-load-btn').removeClass('load');
                              console.error('UFG: Load more failed.');
                         },
                    });
               } else {
                    jQuery('#fg-load-btn').removeClass('load');
                    jQuery('#fg-load-btn').text('No More Result').css({ 'opacity': '00.2', 'pointer-events': 'none' });
               }

          });
     }
     // On Click Load More END

     //Load more logic for loading images if clicked filter has no image on first load START
     if (UFGJS.LoadMore == 'on') {
          jQuery(".filters").on("click", function (e) {

               // Get click filter button data-filter value
               var dataFilterValue = jQuery(this).attr("data-fname");  // data fname value like: .buildings-1
               console.log(dataFilterValue);
               if (dataFilterValue != "none") {
                    // Find all anchor tags with the clicked filter class
                    var FilterValueCount = jQuery("a" + dataFilterValue).length;
                    console.log(FilterValueCount);
                    // Check if there are no anchor tags with the clicked class then load images
                    if (FilterValueCount > 0) {
                         console.log("There are " + FilterValueCount + " anchor tags with the " + dataFilterValue + " class.");
                    } else {
                         console.log("There are no anchor tags with the " + dataFilterValue + " class.");

                         jQuery(this).addClass('load');
                         e.preventDefault();
                         var CalTotalItemInFilter = 0;
                         var CalTotalLoadedItem = 0;
                         var targetFilter = jQuery('.ufg-filter-container button.active').map(function () { return jQuery(this).val(); }).get();
                         // Load Images according to filter (ALL)
                         if (targetFilter == '*') {
                              CalTotalItemInFilter = parseInt(UFGJS.TotalImages); //31
                              CalTotalLoadedItem = CalTotalLoadedItem + jQuery('.ufg-thumbnail').length;
                         }
                         // Load Images according to filter (Not ALL)
                         if (targetFilter != '*') {
                              jQuery(targetFilter).each(function (index, val) {
                                   var isLastElement = index == targetFilter.length - 1;
                                   if (isLastElement && filter_image && filter_image[val]) {
                                        CalTotalItemInFilter = CalTotalItemInFilter + filter_image[val].length;
                                        CalTotalLoadedItem = CalTotalLoadedItem + jQuery('.ufg-gallery-container >' + 'div.' + val).length;
                                   }
                              });
                         }

                         var ufg_limit_start = CalTotalLoadedItem;
                         var ufg_limit_end = parseInt(ufg_limit_start) + parseInt(UFGJS.LoadLimit); // 126 gallery.php

                         // Get all loaded items
                         var get_all_items = jQuery('.count_attached').map(function () { return jQuery(this).val(); }).get();
                         // Check images limit
                         if (CalTotalItemInFilter > CalTotalLoadedItem) {
                              jQuery.ajax({
                                   dataType: 'html',
                                   type: 'POST',
                                   url: location.href,
                                   cache: false,
                                   data: '&ufg_security=' + UFGJS.LoadMoreNonce + '&ufg_limit_start=' + ufg_limit_start + '&ufg_limit_end=' + ufg_limit_end + '&targetFilter=' + targetFilter + '&CalTotalLoadedItem=' + CalTotalLoadedItem + '&get_all_items=' + get_all_items,
                                   complete: function () { },
                                   success: function (response) {

                                        $node = jQuery(response).find('.ufg_result');
                                        if ($node.length > 0) {
                                             $grid.append($node).isotope('insert', $node);
                                              $node.imagesLoaded().progress(function (instance, image) {
                                                   jQuery(image.img).closest('.ufg-img-wrap').addClass('loaded');
                                                   $grid.isotope('layout');
                                              }).always(function () {
                                                   $grid.isotope('layout');
                                              });
                                        } else {
                                             jQuery('#fg-load-btn').text('No More Result').css({ 'opacity': '00.2', 'pointer-events': 'none' });
                                        }
                                        setTimeout(function () {
                                             jQuery('#fg-load-btn').removeClass('load');
                                        }, 800);

                                        jQuery('.ufg-thumbnail').removeClass("ufg_result");
                                   },
                                   error: function () {
                                        jQuery('#fg-load-btn').removeClass('load');
                                        console.error('UFG: Filter load failed.');
                                   },
                              });
                         } else {
                              jQuery('#fg-load-btn').removeClass('load');
                              jQuery('#fg-load-btn').text('No More Result').css({ 'opacity': '00.2', 'pointer-events': 'none' });
                         }
                    } //end else 
               } // end if
          });
     }
     //Load more logic for loading images if clicked filter has no image on first load END

     // Dropdown Filter Change Logic
     if (jQuery('.ufg-filter-dropdown').length > 0) {
          jQuery('.ufg-filter-dropdown').on('change', function(e) {
               var value = jQuery(this).val();
               var selected_filter = value === '*' ? '*' : "." + value;
               
               // Update URL hash for deep linking
               if (UFGJS.EnableDeepLinking) {
                    if (value === '*') {
                         if (window.location.hash.indexOf('ufg-filter=') > -1) {
                              if (history.replaceState) {
                                   history.replaceState(null, null, window.location.pathname + window.location.search);
                              } else {
                                   window.location.hash = '';
                              }
                         }
                    } else {
                         if (history.replaceState) {
                              history.replaceState(null, null, '#' + 'ufg-filter=' + encodeURIComponent(value));
                         } else {
                              window.location.hash = 'ufg-filter=' + encodeURIComponent(value);
                         }
                    }
               }
               
               // Update hidden inputs for tracking
               jQuery('#ufg_current_clicked_filter_id').val(value);
               
               var searchQuery = jQuery('.ufg-search-input').length > 0 ? jQuery('.ufg-search-input').val().toLowerCase() : '';
               $grid.isotope({
                    filter: function() {
                        var $this = jQuery(this);
                        var searchResult = searchQuery ? $this.text().toLowerCase().indexOf(searchQuery) > -1 : true;
                        var categoryResult = selected_filter && selected_filter !== '*' ? $this.is(selected_filter) : true;
                        return searchResult && categoryResult;
                    }
               });

               // Handle Lightbox grouping update
               if (value == "*") {
                    if (UFGJS.Lightbox === true || UFGJS.Lightbox === "true" || UFGJS.Lightbox === 1) {
                         jQuery('.ufg-lightbox').removeData();
                         jQuery('.ufg-lightbox').attr('data-lightbox', 'ufg-lightbox');
                    }
               } else {
                    if (UFGJS.Lightbox === true || UFGJS.Lightbox === "true" || UFGJS.Lightbox === 1) {
                         jQuery('a.ufg-lightbox').removeAttr('data-lightbox');
                         var lightbox_class_name = "ufg-lightbox-" + value;
                         jQuery('.' + value).attr('data-lightbox', lightbox_class_name);
                    }
               }

               // Load more count update and AJAX fetch if no images exist on page for this filter
               if (value !== "none" && value !== "*") {
                    var FilterValueCount = jQuery("a." + value).length;
                    if (FilterValueCount === 0 && UFGJS.LoadMore == 'on') {
                         var dropdownEl = jQuery(this);
                         dropdownEl.addClass('load');
                         
                         var CalTotalItemInFilter = 0;
                         var CalTotalLoadedItem = 0;
                         var targetFilter = [value];
                         
                         if (filter_image && filter_image[value]) {
                              CalTotalItemInFilter = filter_image[value].length;
                         }
                         var ufg_limit_start = 0;
                         var ufg_limit_end = parseInt(UFGJS.LoadLimit);
                         var get_all_items = jQuery('.count_attached').map(function () { return jQuery(this).val(); }).get();
                         
                         if (CalTotalItemInFilter > 0) {
                              jQuery.ajax({
                                   dataType: 'html',
                                   type: 'POST',
                                   url: location.href,
                                   cache: false,
                                   data: '&ufg_security=' + UFGJS.LoadMoreNonce + '&ufg_limit_start=' + ufg_limit_start + '&ufg_limit_end=' + ufg_limit_end + '&targetFilter=' + targetFilter + '&CalTotalLoadedItem=' + CalTotalLoadedItem + '&get_all_items=' + get_all_items,
                                   success: function (response) {
                                        var $node = jQuery(response).find('.ufg_result');
                                        if ($node.length > 0) {
                                             $grid.append($node).isotope('insert', $node);
                                             $node.imagesLoaded().progress(function (instance, image) {
                                                  jQuery(image.img).closest('.ufg-img-wrap').addClass('loaded');
                                                  $grid.isotope('layout');
                                             }).always(function () {
                                                  $grid.isotope('layout');
                                             });
                                        }
                                        dropdownEl.removeClass('load');
                                        jQuery('.ufg-thumbnail').removeClass("ufg_result");
                                   },
                                   error: function () {
                                        dropdownEl.removeClass('load');
                                        console.error('UFG: Filter dropdown load failed.');
                                   }
                              });
                         } else {
                              dropdownEl.removeClass('load');
                         }
                    }
               }

               if (UFGJS.LoadMore == 'on') {
                    var CalTotalItemInFilter = 0;
                    var CalTotalLoadedItem = 0;
                    if (value == '*') {
                         CalTotalItemInFilter = parseInt(UFGJS.TotalImages);
                         CalTotalLoadedItem = jQuery('.ufg-thumbnail').length;
                    } else {
                         if (filter_image && filter_image[value]) {
                              CalTotalItemInFilter = filter_image[value].length;
                              CalTotalLoadedItem = jQuery('.ufg-gallery-container > div.' + value).length;
                         }
                    }
                    if (CalTotalItemInFilter == CalTotalLoadedItem) {
                         jQuery('#fg-load-btn').html('No More Result').css({ 'opacity': '00.2', 'pointer-events': 'none' });
                    } else {
                         jQuery('#fg-load-btn').html(UFGJS.LoadBtnText + ' <i class="fas fa-circle-notch fa-spin"></i>').css({ 'opacity': '1', 'pointer-events': 'auto' });
                    }
               }
          });
     }

     // Search Box Logic
     if (jQuery('.ufg-search-input').length > 0) {
          jQuery('.ufg-search-input').on('keyup', function() {
               var searchQuery = jQuery(this).val().toLowerCase();
               var activeBtn = jQuery('.ufg-filter-container button.active');
               var selected_filter = '*';
               
               if (activeBtn.length > 0 && activeBtn.val() !== '*') {
                    selected_filter = "." + activeBtn.val();
               }

               $grid.isotope({
                    filter: function() {
                        var $this = jQuery(this);
                        var searchResult = searchQuery ? $this.text().toLowerCase().indexOf(searchQuery) > -1 : true;
                        var categoryResult = selected_filter && selected_filter !== '*' ? $this.is(selected_filter) : true;
                        return searchResult && categoryResult;
                    }
               });
          });
     }
});

// Filter level Controls
jQuery(document).ready(function() {
     var allSublevelButtons = 'button.ufg-level-one-button, button.ufg-level-two-button, button.ufg-level-three-button, button.ufg-level-four-button';
     if (UFGJS.ChildFilterEffect == 'fade') { //53 settings.php
          jQuery(allSublevelButtons).fadeTo(200, 0.1).css('pointer-events', 'none');
     } else {
          jQuery(allSublevelButtons).css('display', 'none');
     }
     updateFilterGroupVisibility();

     // Smoothly reveal filter/search elements once sublevels are hidden
     jQuery('.fg-content-wrapper .ufg-filter-container, .fg-content-wrapper .ufg-search-container, .fg-content-wrapper .ufg-combined-row, .fg-content-wrapper .ufg-uncombined-search').css({
          'opacity': '1',
          'pointer-events': 'auto'
     });
});

function updateFilterGroupVisibility() {
     jQuery('.filter-group').each(function() {
          var group = jQuery(this);
          if (group.hasClass('ufg-parent-filters')) {
               group.removeClass('ufg-hide-group');
               return;
          }
          var hasVisibleButtons = false;
          group.find('button.filters').each(function() {
               if (jQuery(this).css('display') !== 'none' && jQuery(this).css('opacity') !== '0.1') {
                    hasVisibleButtons = true;
               }
          });
          if (hasVisibleButtons) {
               group.removeClass('ufg-hide-group');
          } else {
               group.addClass('ufg-hide-group');
          }
     });
}

jQuery('#1evel1-all').addClass('active-filter active');
function filter(id, value) {
     jQuery('button.filters').removeClass('active-filter active');

     if (value === '*') {
          var allSublevels = 'button.ufg-level-one-button, button.ufg-level-two-button, button.ufg-level-three-button, button.ufg-level-four-button';
          if (UFGJS.ChildFilterEffect == 'fade') {
               jQuery(allSublevels).fadeTo(200, 0.1).css('pointer-events', 'none');
          } else {
               jQuery(allSublevels).css('display', 'none');
          }
          jQuery('#1evel1-all').addClass('active-filter active');
     } else {
          var clickedBtn = jQuery('#' + id);
          clickedBtn.addClass('active-filter active');

          var classes = clickedBtn.attr('class').split(/\s+/);
          classes.forEach(function(cls) {
               if (cls && cls !== 'ufg-btn' && cls !== 'ufg-btn-3' && cls !== 'filters' && cls !== 'ufg-filter-button' && cls !== 'active-filter' && cls !== 'active') {
                    jQuery('button.filters[value="' + cls + '"]').addClass('active-filter');
               }
          });

          var currentLevel = 1;
          if (id.indexOf('level2-') === 0) currentLevel = 2;
          else if (id.indexOf('level3-') === 0) currentLevel = 3;
          else if (id.indexOf('level4-') === 0) currentLevel = 4;
          else if (id.indexOf('level5-') === 0) currentLevel = 5;

          var nextLevelClass = '';
          if (currentLevel === 1) nextLevelClass = 'ufg-level-one-button';
          else if (currentLevel === 2) nextLevelClass = 'ufg-level-two-button';
          else if (currentLevel === 3) nextLevelClass = 'ufg-level-three-button';
          else if (currentLevel === 4) nextLevelClass = 'ufg-level-four-button';

          var hideClasses = [];
          if (currentLevel < 2) hideClasses.push('ufg-level-one-button');
          if (currentLevel < 3) hideClasses.push('ufg-level-two-button');
          if (currentLevel < 4) hideClasses.push('ufg-level-three-button');
          if (currentLevel < 5) hideClasses.push('ufg-level-four-button');

          if (hideClasses.length > 0) {
               var hideSelector = 'button.' + hideClasses.join(', button.');
               if (UFGJS.ChildFilterEffect == 'fade') {
                    jQuery(hideSelector).fadeTo(200, 0.1).css('pointer-events', 'none');
               } else {
                    jQuery(hideSelector).css('display', 'none');
               }
          }

          if (nextLevelClass !== '') {
               var showSelector = 'button.' + nextLevelClass + '.' + value;
               if (UFGJS.ChildFilterEffect == 'fade') {
                    jQuery(showSelector).fadeTo(200, 1).css('pointer-events', 'auto');
               } else {
                    jQuery(showSelector).css('display', 'inline-block');
               }
          }
     }

     if (value == "*") {
          if (UFGJS.Lightbox === true || UFGJS.Lightbox === "true" || UFGJS.Lightbox === 1) {
               jQuery('.ufg-lightbox').removeData();
               jQuery('.ufg-lightbox').attr('data-lightbox', 'ufg-lightbox');
          }
     } else {
          if (UFGJS.Lightbox === true || UFGJS.Lightbox === "true" || UFGJS.Lightbox === 1) {
               jQuery('a.ufg-lightbox').removeAttr('data-lightbox');
               var lightbox_class_name = "ufg-lightbox-" + value;
               jQuery('.' + value).attr('data-lightbox', lightbox_class_name);
          }
     }
     updateFilterGroupVisibility();
}

if (UFGJS.Lightbox === true || UFGJS.Lightbox === "true" || UFGJS.Lightbox === 1) {
     jQuery(document).ready(function () {
          lightbox.option({
               'alwaysShowNavOnTouchDevices': false,
               'albumLabel': "%1 of %2",
               'disableScrolling': true,
               'fadeDuration': 600,
               'fitImagesInViewport': true,
               'imageFadeDuration': 600,
               'positionFromTop': 50,
               'resizeDuration': 700,
               'showImageNumberLabel': UFGJS.LightboxNumbering, //623 settings.php
               'wrapAround': true,
          });
     });
}

// selected filter on gallery first load (supports deep linking / URL filtering)
jQuery(document).ready(function () {
     jQuery(function () {
          setTimeout(function () {
               var urlFilter = '';
               if (UFGJS.EnableDeepLinking) {
                    // Parse Hash
                    var hash = window.location.hash;
                    if (hash && hash.indexOf('ufg-filter=') > -1) {
                         var parts = hash.split('ufg-filter=');
                         if (parts.length > 1) {
                              urlFilter = decodeURIComponent(parts[1].split('&')[0]);
                         }
                    }
                    // Parse Query Param if no Hash filter
                    if (!urlFilter) {
                         var urlParams = new URLSearchParams(window.location.search);
                         if (urlParams.has('ufg_filter')) {
                              urlFilter = urlParams.get('ufg_filter');
                         }
                    }
               }

               if (urlFilter) {
                    // Try to find the button with this filter value
                    var targetButton = jQuery('button.filters[value="' + urlFilter + '"]');
                    if (targetButton.length > 0) {
                         var dataFilter = targetButton.attr('data-filter');
                         if (dataFilter && dataFilter !== '*') {
                              var selectors = dataFilter.trim().split(/\s+/);
                              selectors.forEach(function(sel) {
                                   var val = sel.replace('.', '');
                                   jQuery('button.filters[value="' + val + '"]').trigger('click');
                              });
                         } else {
                              targetButton.trigger('click');
                         }
                         return;
                    }
                    
                    // If it is a dropdown filter:
                    var targetDropdown = jQuery('select.ufg-filter-dropdown');
                    if (targetDropdown.length > 0 && targetDropdown.find('option[value="' + urlFilter + '"]').length > 0) {
                         targetDropdown.val(urlFilter).trigger('change');
                         return;
                    }
               }

               // Fallback to default selected filter if no URL filter
               if (UFGJS.SelectedFltrBtnId != "") {
                    if (UFGJS.SelectedFltrBtnId === 'none') {
                         jQuery('.ufg-gallery-' + UFGJS.GalleryId).isotope({ filter: '.ufg-no-match-filter' });
                         jQuery('button.filters').removeClass('active-filter active');
                    } else {
                         jQuery('#' + UFGJS.SelectedFltrBtnId).trigger('click');
                    }
               }
          }, 100);
     });
});
