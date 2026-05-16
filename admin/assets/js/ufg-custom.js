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

     // layout Isotope after each image loads
     $grid.imagesLoaded().progress(function (instance, image) {
          jQuery(image.img).closest('.ufg-img-wrap').addClass('loaded');
          $grid.isotope('layout');
     }).always(function () {
          $grid.isotope('layout');
     });

     // Ensure layout is refreshed after window is fully loaded (fixes Firefox timing issues)
     jQuery(window).on('load', function () {
          $grid.isotope('layout');
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
          $grid.isotope({
               filter: selected_filter
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
});

// Filter level Controls
if (UFGJS.ChildFilterEffect == 'fade') { //53 settings.php
     jQuery('button.ufg-level-one-button').fadeTo(200, 0.1).css('pointer-events', 'none');  //hide all level 1 buttons
} else {
     jQuery('button.ufg-level-one-button').css('display', 'none');  //hide all level 1 buttons
}

jQuery('#1evel1-all').addClass('active-filter active');
function filter(id, value) {
     jQuery('#1evel1-all').removeClass('active-filter');
     //console.log(id);
     //console.log(value);
     var ufg_btn_text = "";
     var ufg_btn_text2 = "";
     var ufg_current_clicked_filter_id = "";
     var ufg_current_clicked_filter_level = "";
     var ufg_last_clicked_filter_id = "";
     var ufg_last_clicked_filter_level = "";
     var ufg_last_clicked_filter_parent_id = "";

     ufg_current_clicked_filter_id = jQuery("#ufg_current_clicked_filter_id").val(); // get last clicked filter id
     ufg_current_clicked_filter_level = jQuery("#ufg_current_clicked_filter_level").val(); // set current clicked filter level
     ufg_last_clicked_filter_id = jQuery("#ufg_last_clicked_filter_id").val(); // get last clicked filter id
     ufg_last_clicked_filter_level = jQuery("#ufg_last_clicked_filter_level").val(); // get last clicked filter level

     ufg_current_clicked_parent_filter_id = jQuery("#ufg_current_clicked_parent_filter_id").val(); // get current clicked parent filter id
     ufg_last_clicked_filter_parent_id = jQuery("#ufg_last_clicked_filter_parent_id").val(); // get last clicked parent filter id

     // set current - initials case
     if (ufg_current_clicked_filter_id == "") {
          //get current clicked filter level
          //console.log(id.match("1evel1"));
          if (id.match("1evel1")) { ufg_current_clicked_filter_level = "1evel1"; }
          //console.log(ufg_current_clicked_filter_level);
          if (id.match("level2")) { ufg_current_clicked_filter_level = "level2"; }
          //console.log(ufg_current_clicked_filter_level);
          if (id.match("level3")) { ufg_current_clicked_filter_level = "level3"; }
          //console.log(ufg_current_clicked_filter_level);

          // set filter id and get
          jQuery("#ufg_current_clicked_filter_id").val(id);
          jQuery("#ufg_current_clicked_filter_level").val(ufg_current_clicked_filter_level);

          jQuery("#ufg_last_clicked_filter_id").val(id);
          jQuery("#ufg_last_clicked_filter_level").val(ufg_current_clicked_filter_level);

          //console.log(ufg_last_clicked_filter_parent_id);
          if (ufg_current_clicked_filter_level == "1evel1") {
               jQuery("#ufg_last_clicked_filter_parent_id").val(id);
          }

          // set current last clicked parent filter id
          jQuery("#ufg_current_clicked_parent_filter_id").val(id);
          jQuery("#ufg_last_clicked_filter_parent_id").val(id);

     } else {

          // transfer current filter to last filter (transfer before getting filter level)
          jQuery("#ufg_last_clicked_filter_id").val(ufg_current_clicked_filter_id);
          jQuery("#ufg_last_clicked_filter_level").val(ufg_current_clicked_filter_level);

          //get current clicked filter level
          //console.log(id.match("1evel1"));
          if (id.match("1evel1")) { ufg_current_clicked_filter_level = "1evel1"; }
          //console.log(ufg_current_clicked_filter_level);
          if (id.match("level2")) { ufg_current_clicked_filter_level = "level2"; }
          //console.log(ufg_current_clicked_filter_level);
          if (id.match("level3")) { ufg_current_clicked_filter_level = "level3"; }
          //console.log(ufg_current_clicked_filter_level);

          // set current filters
          jQuery("#ufg_current_clicked_filter_id").val(id);
          jQuery("#ufg_current_clicked_filter_level").val(ufg_current_clicked_filter_level);

          ufg_last_clicked_filter_id = jQuery("#ufg_last_clicked_filter_id").val(); // get last clicked filter id
          ufg_last_clicked_filter_level = jQuery("#ufg_last_clicked_filter_level").val(); // get last clicked filter level

          // remove check icon on last clicked filter - if same level2 filter button clicked
          if (ufg_current_clicked_filter_level == "level2" && ufg_last_clicked_filter_level == "level2") {
               // remove check icon from last clicked filter button
               jQuery("#" + ufg_last_clicked_filter_id).removeClass('active-filter'); // get html value
          }

          // when transferring filter from level2 to level1
          if (ufg_current_clicked_filter_level === "1evel1") {
               // transfer last clicked parent filter id to current 
               jQuery("#ufg_current_clicked_parent_filter_id").val(id);
               jQuery("#ufg_last_clicked_parent_filter_id").val(ufg_current_clicked_parent_filter_id);

               // remove check icon from last clicked filter button
               jQuery("#" + ufg_current_clicked_parent_filter_id).removeClass('active-filter'); // get html value
               jQuery("#" + ufg_last_clicked_filter_id).removeClass('active-filter'); // get html value
          }
     }

     jQuery("#" + id).addClass('active-filter');

     // hide all level 2 button
     if (ufg_current_clicked_filter_level != "level2") { // display only level one filter accordingly parent filters clicked
          if (UFGJS.ChildFilterEffect == 'fade') { //53 settings.php
               jQuery('button.ufg-level-one-button').fadeTo(200, 0.1).css('pointer-events', 'none'); //hide all level 1 buttons
          } else {
               jQuery('button.ufg-level-one-button').css('display', 'none');  //hide all level 1 buttons
          }
     }

     //filtering
     if (value == "*") {
          // show all filters
          if (UFGJS.ChildFilterEffect == 'fade') { //53 settings.php
               jQuery('button.ufg-parent-filters').fadeTo(200, 1).css('pointer-events', 'auto');; //display all filters
          } else {
               jQuery('button.ufg-parent-filters').css('display', 'inline-block');  //display all filters
          }

          // lightbox - remove data attribute and dynamic add lightbox data-lightbox to anchor tag
          if (UFGJS.Lightbox === true || UFGJS.Lightbox === "true" || UFGJS.Lightbox === 1) {
               jQuery('.ufg-lightbox').removeData();
               jQuery('.ufg-lightbox').attr('data-lightbox', 'ufg-lightbox'); // add data-lightbox for all images cycle in lightbox
          }
     } else {
          // remove data-lightbox attribute from all ufg-thumbnail
          if (UFGJS.Lightbox === true || UFGJS.Lightbox === "true" || UFGJS.Lightbox === 1) {
               jQuery('a.ufg-lightbox').removeAttr('data-lightbox');
          }

          // show hide images
          jQuery('button.' + value).fadeTo(200, 1).css('pointer-events', 'auto'); //display only clicked filters button and images

          // dynamically add lightbox data-filter classes accordingly parent and sub filter clicked
          if (UFGJS.Lightbox === true || UFGJS.Lightbox === "true" || UFGJS.Lightbox === 1) {
               var lighbox_class_name = "ufg-lightbox-" + value;
               jQuery('.' + value).attr('data-lightbox', lighbox_class_name); // add data filter for parent filters
          }
     }
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

// selected filter on gallery first load start
if (UFGJS.SelectedFltrBtnId != "") { //15
     jQuery(document).ready(function () {
          jQuery(function () {
               // it will wait for 1 sec. and then will fire
               setTimeout(function () {
                    // click on filter button
                    jQuery('#' + UFGJS.SelectedFltrBtnId).trigger('click'); //15
               }, 50);
          });
     });
}
// selected filter on gallery first load end
