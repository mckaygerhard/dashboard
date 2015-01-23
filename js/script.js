/**
 * ownCloud - dashboard
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author Florian Steffens <webmaster@freans.de>
 * @copyright Florian Steffens 2014
 */

(function ($, OC) {

	$(document).ready(function () {

        dashboard.loadEnabledWidgets();
        dashboard.addEnabledWidgets();
        dashboard.initControl();

        $('#widgets').on('change', '.widget .settings .setting', function () {
            dashboard.setConfig($(this).data('wiid'),$(this).attr('name'), $(this).val(),this);
        });

        $('#widgets').on('hover', '.widget', function () {
            var wIId = $(this).data('wiid');
            dashboard.setHoverWIId(wIId);
        });

        $('#widgets').on('click', '.removeWidget', function () {
            var wIId = $(this).data('wiid');
            dashboard.removeWidget(wIId);
        });

	});

})(jQuery, OC);



dashboard = {

    // holds all enabled widgets as array
    enabledWidgets: [],

    // holds all wIIds that are not yet added
    setToAdd: [],

    // if the cursor hovers a widget, the hovered wIId is set here
    hoverWIId: null,

    // array of interval for refreshing the widgets
    refreshIntervals: [],

    // callback that will be called after a widget refresh
    widgetCallback: [],

    // set or unset wIId from hovered widget
    setHoverWIId: function(wIId) {
        if( dashboard.hoverWIId == wIId ) {
            dashboard.hoverWIId = null;
        } else {
            dashboard.hoverWIId = wIId;
        }
        dashboard.hideOrShowWidgetInformation();
    },

    // removes a widget
    removeWidget: function (wIId) {
        dashboard.showWaitSymbol();
        var url = OC.generateUrl('/apps/dashboard/widget/remove');
        var data = {
            wIId: wIId
        };
        $.post(url, data).success(function (response) {
            $('#widgets .widget.' + wIId).fadeOut();
        });
        dashboard.hideWaitSymbol();
    },

    // on-mouse-over a widget, show all div with class hoverInfo
    // on-mouse-out hide the divs
    // you can set data-opacityhover to a numeric value
    // you can set data-opacitynormal to a numeric value
    hideOrShowWidgetInformation: function () {
        if( dashboard.hoverWIId == null) {
            var opacity;
            $('#widgets .widget .hoverInfo').each( function () {
                if( typeof $(this).data('opacitynormal') === 'undefined' ) {
                    opacity = jQuery.parseJSON( '{ "opacity": "0"}' );
                } else {
                    opacity = jQuery.parseJSON( '{ "opacity": "' + $(this).data('opacitynormal') + '"}' );
                }

                $(this).animate(
                    opacity,
                    100
                );
            });
        } else {
            $('#widgets .widget.' + dashboard.hoverWIId + ' .hoverInfo').each( function () {
                var opacity;
                if( typeof $(this).data('opacityhover') === 'undefined' ) {
                    opacity = jQuery.parseJSON( '{ "opacity": "1"}' );
                } else {
                    opacity = jQuery.parseJSON( '{ "opacity": "' + $(this).data('opacityhover') + '"}' );
                }

                $(this).animate(
                    opacity,
                    100
                );
            });
        }
    },

    // get enabled widgets as array
    addEnabledWidgets: function () {
        dashboard.showWaitSymbol();
        if(dashboard.enabledWidgets.length == 0) {
            dashboard.hideWaitSymbol();
            dashboard.showOverlay();
        } else {
            dashboard.setToAdd = dashboard.enabledWidgets;
            dashboard.addOneFromSetToAdd();
        }
    },

    // add the first index wId from setToAdd array
    addOneFromSetToAdd: function() {
        if( dashboard.setToAdd.length != 0 ) {
            dashboard.addCompleteWidget(dashboard.setToAdd[0], dashboard.addOneFromSetToAdd);
            dashboard.setToAdd.splice(0,1);
        } else {
            dashboard.hideWaitSymbol();
        }
    },

    // show enabled widgets
    loadEnabledWidgets: function () {
        dashboard.enabledWidgets = $('#app-content').data('enabledwidgets');
    },

    // fetch the html from a enabled widget and append it
    addCompleteWidget: function (wIId, callback) {
        dashboard.showWaitSymbol();
        var url = OC.generateUrl('/apps/dashboard/widget/complete');
        var data = {
            wIId: wIId
        };

        var posting = $.post(url, data);
        posting.success(function (response) {
            var html =  '<div class="widget ' + response.wId + ' ' + wIId + ' status-' + response.status + ' dimension-' + response.dimension + '" data-refresh="' + response.refresh + '" data-wiid="' + wIId + '" data-mode="content">' +
                            response.widgetHtml +
                        '</div>';
            $('#widgets').append( html );

            // bind event for widget content reloading
            $('#widgets .widget.' + wIId + ' .heading h1 span.iconReload').on('click', function () {
                dashboard.showWaitSymbol();
                $(this).removeClass('icon-play');
                $(this).addClass('icon-loading-small');
                dashboard.refreshWidget(wIId);
            });

            // bind event for showing settings
            $('#widgets .widget.' + wIId + ' .heading h1 span.iconSettings').on('click', function () {
                if( $( '#widgets .widget.' + wIId ).data('mode') == 'content' ) {
                    $('#widgets .widget.' + wIId + ' .content').fadeOut();
                    $('#widgets .widget.' + wIId + ' .settings').fadeIn();
                    $('#widgets .widget.' + wIId).data('mode', 'settings');
                } else {
                    $('#widgets .widget.' + wIId + ' .content').fadeIn();
                    $('#widgets .widget.' + wIId + ' .settings').fadeOut();
                    $('#widgets .widget.' + wIId).data('mode', 'content');
                }
            });

            var refresh = response.refresh * 1000;
            if( refresh != 0 ) {
                dashboard.refreshIntervals[wIId] = setInterval(
                    function() {
                        if(dashboard.hoverWIId != wIId) {
                            dashboard.refreshWidget(wIId);
                        }
                    },
                    refresh
                );
            }
            dashboard.hideWaitSymbol();
        });
        posting.done(function() {
            callback();
            dashboard.hideOrShowWidgetInformation();

            var split = wIId.split('-');
            var wId   = split[0];
            if( dashboard.widgetCallback[wId] != 'undefined' ) {
                dashboard.widgetCallback[wId]();
            }
        });
    },

    // fetch the content-html and change the actual one
    refreshWidget: function (wIId) {
        var url = OC.generateUrl('/apps/dashboard/widget/content');
        var data = {
            wIId: wIId
        };
        $.post(url, data).success(function (response) {
            dashboard.showWaitSymbol();
            dashboard.setWidgetStatus(wIId, response.status);
            $('#widgets .widget.' + wIId + ' .content').html( response.widgetHtml );
            $('#widgets .widget.' + wIId + ' .heading h1 span.iconReload').removeClass('icon-loading-small');
            $('#widgets .widget.' + wIId + ' .heading h1 span.iconReload').addClass('icon-play');
            dashboard.hideOrShowWidgetInformation();

            var split = wIId.split('-');
            var wId   = split[0];
            if( dashboard.widgetCallback[wId] != 'undefined' ) {
                dashboard.widgetCallback[wId]();
            }
            dashboard.hideWaitSymbol();
        });
        $.post(url, data).done(function () {
            dashboard.hideWaitSymbol();
        });
    },

    // call a specific method in the widgetController
    callWidgetMethod: function (wIId, method, value) {
        var url = OC.generateUrl('/apps/dashboard/widget/call');
        var data = {
            wIId: wIId,
            method: method,
            value: value
        };
        return $.post(url, data);
    },

    // change the css class depending by the status for the widget
    setWidgetStatus: function (wIId, status) {
        $('#widgets .widget.' + wIId).removeClass('status-0');
        $('#widgets .widget.' + wIId).removeClass('status-1');
        $('#widgets .widget.' + wIId).removeClass('status-2');
        $('#widgets .widget.' + wIId).removeClass('status-3');
        $('#widgets .widget.' + wIId).addClass('status-' + status );
    },

    // change the css class to the new dimension
    setWidgetDimension: function(wIId, dimension) {
        $('#widgets .widget.' + wIId).removeClass('dimension-1x1');
        $('#widgets .widget.' + wIId).removeClass('dimension-1x2');
        $('#widgets .widget.' + wIId).removeClass('dimension-1x3');
        $('#widgets .widget.' + wIId).removeClass('dimension-2x1');
        $('#widgets .widget.' + wIId).removeClass('dimension-2x2');
        $('#widgets .widget.' + wIId).removeClass('dimension-2x3');
        $('#widgets .widget.' + wIId).removeClass('dimension-3x1');
        $('#widgets .widget.' + wIId).removeClass('dimension-3x2');
        $('#widgets .widget.' + wIId).removeClass('dimension-3x3');
        $('#widgets .widget.' + wIId).addClass('dimension-' + dimension );
    },

    // save a config value in the db
    setConfig: function (wIId, key, value, e) {
        var url = OC.generateUrl('/apps/dashboard/widget/config');
        var data = {
            wIId: wIId,
            key: key,
            value: value
        };
        $.post(url, data).success(function () {
            $(e).animate({
                backgroundColor: '#CDFECD'
            }, 100);
            setTimeout(function() {
                $(e).animate({
                    backgroundColor: 'white'
                }, 500);
            }, 500);
            if( key == 'dimension' ) {
                dashboard.setWidgetDimension(wIId, value);
            }
        });
    },

    // set bindings for the widget control center
    initControl: function () {
        dashboard.loadAvailableWidgets();
        $('.controlPlus').on('click', dashboard.showOverlay);
        $('.overlay').on('click', dashboard.hideOverlay);
        $('.overlayArea .close').on('click', dashboard.hideOverlay);
    },

    showOverlay: function () {
        $('.overlayArea').fadeIn();
        $('.overlay').fadeIn();
    },

    hideOverlay: function () {
        $('.overlayArea').fadeOut();
        $('.overlay').fadeOut();
    },

    // load available widgets for widget-control
    loadAvailableWidgets: function () {
        var url = OC.generateUrl('/apps/dashboard/widget/getAvailable');
        var data = {};
        $.post(url, data).success(function (response) {
            for(var i = 0; i < response.length; i++) {
                var html = '<div class="widget" data-wid="' + response[i]['wId'] + '">' +
                    '<h2>' + response[i]['name'] + '</h2>' +
                    '<div class="icon">' +
                    '<img src="' + response[i]['icon'] + '" alt="' + response[i]['wId'] + ' icon">' +
                    '</div>' +
                    '</div>';
                $('.widgetChoice').append(html);

            }
        });
        $('.widgetChoice').on('click', '.widget', function () {
            var wId = $(this).data('wid');
            dashboard.addNewWidget(wId);
        });
    },

    addNewWidget: function (wId) {
        dashboard.showWaitSymbol();
        dashboard.hideOverlay();
        var url = OC.generateUrl('/apps/dashboard/widget/addNew');
        var data = {
            wId: wId
        };
        $.post(url, data).success(function (response) {
            dashboard.addCompleteWidget(response.wIId);
        });
    },

    hideWaitSymbol: function() {
        $('#app-content .wait').fadeOut();
    },

    showWaitSymbol: function() {
        $('#app-content .wait').fadeIn();
    }
}