;( function( $ ) {
    "use strict";

    if ( 'undefined' == typeof AXiAdminLocalize ) {
        return;
    }

    var AXiAdmin = window.AXiAdmin || {};

    AXiAdmin.fnSelect2 = function() {
        $( '[data-axisys-el="select2"]' ).each( function() {
            var $this = $( this );
            var options = $this.data( 'options' );
            if ( 'undefined' === typeof options ) {
                options = {};
            }
            $this.select2( options );
        });
    };

    AXiAdmin.fnAdminMenu = function() {
        var $promotionLink = $( '#adminmenu > #toplevel_page_axisys a[href^="edit.php?post_type=axi_promotion"]' ),
            $newPromotionLink = $( '#adminmenu a[href^="post-new.php?post_type=axi_promotion"]' );
        if ( AXiAdminLocalize.pagenow == 'axi_promotion' && AXiAdminLocalize.typenow == 'axi_promotion' ) {
            if ( $promotionLink.length && ! $promotionLink.hasClass( 'current' ) ) {
                $promotionLink.addClass( 'current' ).attr( 'aria-current', 'page' );
                $promotionLink.parent().addClass( 'current' );
                if ( $newPromotionLink.length ) {
                    $newPromotionLink.removeClass( 'current' ).removeAttr( 'aria-current' );
                    $newPromotionLink.parent().removeClass( 'current' );
                }
            }
        }
    };

    AXiAdmin.fnCourseMetaBoxes = function() {
        if ( AXiAdminLocalize.pagenow !== 'axi_course' || AXiAdminLocalize.typenow !== 'axi_course' ) {
            return;
        }
        var defaultMode = AXiAdminLocalize.defaultDeliveryMode,
            $form       = $( '#post' ),
            $metaBox    = $( '#axi_course_meta_box' ),
            $dis        = $( '#axifield-discipline' ),
            $ctype      = $( '#axifield-course-type' ),
            $msg        = $( '#axifield-locmode-msg' ),
            $loc        = $( '#axifield-location' ),
            $locv       = $( '[data-axi-sfield="axifield-location"]' ),
            $dlm        = $( '#axifield-delivery-mode' ),
            $dlmv       = $( '[data-axi-sfield="axifield-delivery-mode"]' );

        if ( ! $form.length || ! $metaBox.length || ! $dis.length || ! $ctype.length || ! $msg.length || ! $loc.length || ! $locv.length || ! $dlm.length || ! $dlmv.length ) {
            return;
        }

        var isValid = function( _locv, _dlmv ) {
            var _res = true;
            if ( _locv != 0 ) {
                if ( _dlmv == 0 ) {
                    _res = false;
                }
                else {
                    _res = true;
                }
            } else {
                if ( 0 == _dlmv || defaultMode == _dlmv ) {
                    _res = false;
                }
                else {
                    _res = true;
                }
            }
            return _res;
        };

        var regularChange = function( e ) {
            var $_this = $( this );
            if ( $_this.hasClass( 'axifield-invalid' ) && $( this ).val() >= 0 ) {
                $_this.removeClass( 'axifield-invalid' );
            }
        };

        $dis.on( 'change', regularChange );
        $ctype.on( 'change', regularChange );

        $loc.on( 'change', function() {
            var $_this = $( this );
            var _locv  = $_this.val(),
                _dlmv = $dlm.val();
            $locv.val( _locv );
            if ( 0 == _locv ) {
                $dlm.attr( 'required', true );
                $dlm.parent().removeClass( 'disabled' );
                $dlm.attr( 'disabled', false );
            }
            else {
                $dlm.removeAttr( 'required' );
                $dlm.parent().addClass( 'disabled' );
                $dlm.val( defaultMode );
                $dlmv.val( defaultMode );
                _dlmv = defaultMode;
                $dlm.attr( 'disabled', true );
            }
            if ( ! isValid( _locv, _dlmv ) ) {
                $msg.show();
            }
            else {
                $msg.hide();
            }
        }).change();

        $dlm.on( 'change', function() {
            var $_this = $( this );
            var _dlmv  = $_this.val(),
                _locv  = $loc.val();
            $dlmv.val( _dlmv );
            if ( 0 == _dlmv || defaultMode == _dlmv ) {
                $loc.attr( 'required', true );
                $loc.parent().removeClass( 'disabled' );
                $loc.attr( 'disabled', false );
            }
            else {
                $loc.removeAttr( 'required' );
                $loc.parent().addClass( 'disabled' );
                $loc.val( 0 );
                $locv.val( 0 );
                _locv = 0;
                $loc.attr( 'disabled', true );
            }
            if ( ! isValid( _locv, _dlmv ) ) {
                $msg.show();
            }
            else {
                $msg.hide();
            }
        }).change();

        $form.on( 'submit', function( event ) {
            var _locv  = $locv.val(),
                _dlmv  = $dlmv.val(),
                _disv  = $dis.val(),
                _ctype = $ctype.val();

            if ( ! isValid( _locv, _dlmv ) || _disv <= 0 || _ctype <= 0 ) {
                event.preventDefault();
                $( 'html, body' ).animate({
                    scrollTop: $metaBox.offset().top - 48
                }, 'fast' );

                if ( _disv <= 0 ) {
                    $dis.addClass( 'axifield-invalid' );
                }

                if ( _ctype <= 0 ) {
                    $ctype.addClass( 'axifield-invalid' );
                }
            }
        });
    };

    AXiAdmin.fnPageMetaBoxes = function() {
        if ( AXiAdminLocalize.pagenow !== 'page' || AXiAdminLocalize.typenow !== 'page' ) {
            return;
        }
        var $templateSelect = $( 'select#page_template' ),
            $metaBoxes = $( '#axi_page_meta_box' );
        
        $templateSelect.on( 'change', function() {
            if ( $( this ).val() == 'axisys-tmpl-landing.php' ) {
                $metaBoxes.show();
            }
            else {
                $metaBoxes.hide();
            }
        }).change();
    };

    AXiAdmin.fnPromotionMetaBoxes = function() {
        if ( AXiAdminLocalize.pagenow !== 'axi_promotion' || AXiAdminLocalize.typenow !== 'axi_promotion' ) {
            return;
        }
        var defaultMode = AXiAdminLocalize.defaultDeliveryMode,
            $form                 = $( '#post' ),
            $metaBox              = $( '#axi_promotion_meta_box' ),
            $typeField            = $( '#axifield-promotion-type' ),
            $amountTypeField      = $( '#axifield-promotion-amount-type' ),
            $courseField          = $( '#axifield-course-id' ),
            $disciplineField      = $( '#axifield-discipline-id' ),
            $percentField         = $( '#axifield-promotion-percent' ),
            $amountField          = $( '#axifield-promotion-amount' ),
            $msg                  = $( '#axifield-locmode-msg' ),
            $loccationField       = $( '#axifield-location-id' ),
            $loccationFieldVal    = $( '[data-axi-sfield = "axifield-location-id"]' ),
            $deliveryModeField    = $( '#axifield-delivery-mode-id' ),
            $deliveryModeFieldVal = $( '[data-axi-sfield = "axifield-delivery-mode-id"]' );
    
        if ( ! $form.length || ! $metaBox.length || ! $typeField.length || ! $courseField.length
            || ! $disciplineField.length || ! $percentField.length || ! $amountField.length || ! $msg.length
            || ! $loccationField.length || ! $loccationFieldVal.length || ! $deliveryModeField.length
            || ! $deliveryModeFieldVal.length ) {
            return;
        }
    
        var shouldValidateLocMode = false,
            firstTypeChanged = false;
    
        var isValid = function( _locv, _dmodev ) {
            if ( ! shouldValidateLocMode ) {
                return true;
            }
            var _res = true;
            if ( _locv != 0 ) {
                if ( _dmodev == 0 ) {
                    _res = false;
                }
                else {
                    _res = true;
                }
            } else {
                if ( 0 == _dmodev || defaultMode == _dmodev ) {
                    _res = false;
                }
                else {
                    _res = true;
                }
            }
            return _res;
        };
    
        $loccationField.on( 'change', function() {
            var $_this = $( this );
            var _locv  = $_this.val(),
                _dmodev = $deliveryModeField.val();
            $loccationFieldVal.val( _locv );
            if ( 0 == _locv ) {
                $deliveryModeField.attr( 'required', true );
                $deliveryModeField.parent().removeClass( 'disabled' );
                $deliveryModeField.attr( 'disabled', false );
            }
            else {
                $deliveryModeField.removeAttr( 'required' );
                $deliveryModeField.parent().addClass( 'disabled' );
                $deliveryModeField.val( defaultMode );
                $deliveryModeFieldVal.val( defaultMode );
                _dmodev = defaultMode;
                $deliveryModeField.attr( 'disabled', true );
            }
            if ( ! isValid( _locv, _dmodev ) ) {
                $msg.show();
            }
            else {
                $msg.hide();
            }
        }).change();
    
        $deliveryModeField.on( 'change', function() {
            var $_this = $( this );
            var _dmodev  = $_this.val(),
                _locv  = $loccationField.val();
            $deliveryModeFieldVal.val( _dmodev );
            if ( 0 == _dmodev || defaultMode == _dmodev ) {
                $loccationField.attr( 'required', true );
                $loccationField.parent().removeClass( 'disabled' );
                $loccationField.attr( 'disabled', false );
            }
            else {
                $loccationField.removeAttr( 'required' );
                $loccationField.parent().addClass( 'disabled' );
                $loccationField.val( 0 );
                $loccationFieldVal.val( 0 );
                _locv = 0;
                $loccationField.attr( 'disabled', true );
            }
            if ( ! isValid( _locv, _dmodev ) ) {
                $msg.show();
            }
            else {
                $msg.hide();
            }
        }).change();
    
        $typeField.on( 'change', function() {
            var _thisv = $( this ).val();
            if ( firstTypeChanged ) {
                $loccationField.val( 0 );
                $loccationFieldVal.val( 0 );
                $deliveryModeField.val( 0 );
                $deliveryModeFieldVal.val( 0 );
            }
            switch( _thisv ) {
                case 'generic':
                    $courseField.attr( 'disabled', true );
                    $courseField.val( [] );
                    $courseField.trigger( 'change' );
                    $disciplineField.attr( 'disabled', true );
                    $disciplineField.val( [] );
                    $disciplineField.trigger( 'change' );
                    if ( firstTypeChanged ) {
                        $loccationField.attr( 'disabled', false );
                        $deliveryModeField.attr( 'disabled', false );
                    }
                    shouldValidateLocMode = false;
                    $msg.hide();
                    break;
                case 'discipline':
                    $courseField.attr( 'disabled', true );
                    $courseField.val( [] );
                    $courseField.trigger( 'change' );
                    $disciplineField.attr( 'disabled', false );
                    if ( firstTypeChanged ) {
                        $loccationField.attr( 'disabled', false );
                        $deliveryModeField.attr( 'disabled', false );
                    }
                    shouldValidateLocMode = true;
                    $msg.show();
                    break;
                case 'course':
                    $courseField.attr( 'disabled', false );
                    $disciplineField.attr( 'disabled', true );
                    $disciplineField.val( [] );
                    $disciplineField.trigger( 'change' );
                    if ( ! firstTypeChanged ) {
                        $loccationField.attr( 'disabled', true );
                        $deliveryModeField.attr( 'disabled', true );
                    }
                    shouldValidateLocMode = true;
                    $msg.show();
                    break;
                default:
                    break;
            }
            firstTypeChanged = true;
        }).change();
    
        $percentField.on( 'input', function() {
            var _tv = $( this ).val();
            if ( _tv ) {
                if ( ! $amountField.is( ':disabled' ) ) {
                    $amountField.attr( 'disabled', true );
                    $amountField.val( '' );
                }
            }
            else {
                if ( $amountField.is( ':disabled' ) ) {
                    $amountField.attr( 'disabled', false );
                    $amountField.val( '' );
                }
            }
        });
        $percentField.trigger( 'input' );
    
        $amountField.on( 'input', function() {
            var _tv = $( this ).val();
            if ( _tv ) {
                if ( ! $percentField.is( ':disabled' ) ) {
                    $percentField.attr( 'disabled', true );
                    $percentField.val( '' );
                }
            }
            else {
                if ( $percentField.is( ':disabled' ) ) {
                    $percentField.attr( 'disabled', false );
                    $percentField.val( '' );
                }
            }
        });
        $amountField.trigger( 'input' );
    
        $form.on( 'submit', function( event ) {
            var _typv  = $typeField.val(),
                _prct  = $percentField.val(),
                _pamt  = $amountField.val(),
                _crsv  = $courseField.val(),
                _disv  = $disciplineField.val(),
                _locv  = $loccationFieldVal.val(),
                _dmodev  = $deliveryModeFieldVal.val();
            
            var _valid = true;
    
            if ( _prct || _pamt ) {
                if ( _prct && _pamt ) {
                    _valid = false;
                }
            }
            else {
                _valid = false;
            }
    
            switch( _typv ) {
                case 'generic':
    
                    break;
                case 'discipline':
                    if ( ! isValid( _locv, _dmodev ) || ! _disv ) {
                        _valid = false;
                    }
                    if ( ! _disv ) {
                        $disciplineField.parent().addClass( 'invalid' );
                    }
                    break;
                case 'course':
                    if ( ! isValid( _locv, _dmodev ) || ! _crsv ) {
                        _valid = false;
                    }
                    if ( ! _crsv ) {
                        $courseField.parent().addClass( 'invalid' );
                    }
                    break;
                default:
                    break;
            }
            
            if ( ! _valid ) {
                event.preventDefault();
                $( 'html, body' ).animate({
                    scrollTop: $metaBox.offset().top - 48
                }, 'fast' );
            }
        });
    };

    AXiAdmin.fnACFPromotion = function() {
        if ( AXiAdminLocalize.pagenow !== 'axi_promotion' || AXiAdminLocalize.typenow !== 'axi_promotion' ) {
            return;
        }
        var canBeGeneric = AXiAdminLocalize.canBeGenericPromotion == '1';
        if ( canBeGeneric ) {
            return;
        }
        var $form = $( '#post' ),
            $typeSelectBox = $( '#post #axi_promotion_type_select' ),
            $typeSelect = $( '#post #axi_promotion_type_select select' ),
            valid = true;
        if ( ! $form.length || ! $typeSelectBox.length || ! $typeSelect.length ) {
            return;
        }
        var $errorEl = $( '<div class="acf-notice -error acf-error-message" style="display:none"></div>' );
        $errorEl.html( '<p>' + AXiAdminLocalize.canBeGenericPromotionMsg + '</p>' );
        $typeSelectBox.prepend( $errorEl );
        $typeSelect.on( 'change', function() {
            if ( $typeSelect.val() === 'generic' ) {
                $errorEl.show();
                valid = false;
            }
            else {
                $errorEl.hide();
                valid = true;
            }
        }).change();

        var _fnScroll = function() {
            $( 'html, body' ).animate({
                scrollTop: $typeSelectBox.offset().top - 48
            }, 'fast' );
        };

        $form.on( 'submit', function( event ) {
            if ( ! valid ) {
                event.preventDefault();
                _fnScroll();
            }
        });
    };

    AXiAdmin.fnDisciplineLinkMetaBoxes = function() {
        if ( AXiAdminLocalize.pagenow !== 'edit-axi_discipline_link' ) {
            return;
        }
        var defaultMode = AXiAdminLocalize.defaultDeliveryMode,
            $form    = $( '#addtag, #edittag' ),
            $submits = $( '#addtag [type="submit"], #edittag [type="submit"]' ),
            $msg     = $( '#axifield-locmode-msg' ),
            $loc     = $( '#axifield-location' ),
            $locv    = $( '[data-axi-sfield="axifield-location"]' ),
            $dlm     = $( '#axifield-delivery-mode' ),
            $dlmv    = $( '[data-axi-sfield="axifield-delivery-mode"]' );

        if ( ! $form.length || ! $loc.length || ! $locv.length || ! $dlm.length || ! $dlmv.length ) {
            return;
        }

        var isValid = function( _locv, _dlmv ) {
            var _res = true;
            if ( _locv != 0 ) {
                if ( _dlmv == 0 ) {
                    _res = false;
                }
                else {
                    _res = true;
                }
            } else {
                if ( 0 == _dlmv || defaultMode == _dlmv ) {
                    _res = false;
                }
                else {
                    _res = true;
                }
            }
            return _res;
        };

        $loc.on( 'change', function() {
            var $_this = $( this );
            var _locv  = $_this.val(),
                _dlmv = $dlm.val();
            $locv.val( _locv );
            if ( 0 == _locv ) {
                $dlm.attr( 'required', true );
                $dlm.parent().removeClass( 'disabled' );
                $dlm.attr( 'disabled', false );
            }
            else {
                $dlm.removeAttr( 'required' );
                $dlm.parent().addClass( 'disabled' );
                $dlm.val( defaultMode );
                $dlmv.val( defaultMode );
                _dlmv = defaultMode;
                $dlm.attr( 'disabled', true );
            }
            if ( ! isValid( _locv, _dlmv ) ) {
                $msg.show();
                $submits.attr( 'disabled', true );
            }
            else {
                $msg.hide();
                $submits.attr( 'disabled', false );
            }
        }).change();

        $dlm.on( 'change', function() {
            var $_this = $( this );
            var _dlmv  = $_this.val(),
                _locv  = $loc.val();
            $dlmv.val( _dlmv );
            if ( 0 == _dlmv || defaultMode == _dlmv ) {
                $loc.attr( 'required', true );
                $loc.parent().removeClass( 'disabled' );
                $loc.attr( 'disabled', false );
            }
            else {
                $loc.removeAttr( 'required' );
                $loc.parent().addClass( 'disabled' );
                $loc.val( 0 );
                $locv.val( 0 );
                _locv = 0;
                $loc.attr( 'disabled', true );
            }
            if ( ! isValid( _locv, _dlmv ) ) {
                $msg.show();
                $submits.attr( 'disabled', true );
            }
            else {
                $msg.hide();
                $submits.attr( 'disabled', false );
            }
        }).change();
    };

    var AXiAdminWidget = window.AXiAdminWidget || {};

    AXiAdminWidget.dynamicPostList = {
        add: function( el, id ) {
            var $select = $( el ),
                $wrap = $( el ).closest( '[data-axiel="dynamic-post-list"]' );

            if ( ! $wrap.length || $wrap.attr( 'data-id' ) != id ) {
                return;
            }

            var $list     = $( '[data-axiel-role="display"]', $wrap ),
                $listItem = void 0,
                $opt      = $( 'option[value="' + $select.val() + '"]', $select ),
                $valEl    = $( '[data-axiel-role="value"]', $wrap ),
                vals      = [];

            if ( ! $list.length || ! $valEl.length ) {
                return;
            }

            $listItem = $(
                '<li data-axiel-value="' + $select.val() + '">' +
                    '<a href="javascript:void(0)" onclick="AXiAdminWidget.dynamicPostList.remove(this,\'' + id + '\')" class="remove">×</a>' +
                    '<span class="text">' + $opt.html() + '</span>' +
                '</li>'
            );
            $list.append( $listItem );

            $list.children().each( function() {
                var _v = this.getAttribute( 'data-axiel-value' );
                vals.push( _v );
            });
            $valEl.val( vals.join( ',' ) );

            $opt.attr( 'disabled', true );
            $select.val( 0 );
        },
        remove: function( el, id ) {
            var $list = $( el ).closest( '[data-axiel-role="display"]' ),
                $listItem = $( el.parentNode ),
                $wrap = $( el ).closest( '[data-axiel="dynamic-post-list"]' );

            if ( ! $list.length || ! $listItem.length || ! $wrap.length || $wrap.attr( 'data-id' ) != id ) {
                return;
            }

            var $select = $( '[data-axiel-role="select"]' ),
                $valEl = $( '[data-axiel-role="value"]', $wrap );
            if ( ! $select.length || ! $valEl.length ) {
                return;
            }
            var val  = $listItem.attr( 'data-axiel-value' );
            var $opt = $( 'option[value="' + val + '"]', $select ),
                vals = [];

            if ( $opt.length ) {
                $opt.attr( 'disabled', false );
            }
            $listItem.remove();
            $list.children().each( function() {
                var _v = this.getAttribute( 'data-axiel-value' );
                vals.push( _v );
            });
            $valEl.val( vals.join( ',' ) );
        }
    };

    AXiAdminWidget.imgSliderWidget = {
        add_images: function( event, widget_id ) {
            event.preventDefault();
            var _this = this;
            var frame = wp.media({
                className: 'media-frame axisys-media-frame',
                title : AXiAdminLocalize.imgSliderWidget.frame_title,
                multiple : true,
                library : { type : 'image' },
                button : { text : AXiAdminLocalize.imgSliderWidget.button_title }
            });
            frame.off( 'close' ).on( 'close', function() {
                var attachments = frame.state().get( 'selection' );
                _this.render_images( widget_id, attachments );
            });
            frame.open();
        },

        render_images: function( widget_id, attachments ) {
            var _this = this;
            var image_preview = $( '#' + widget_id + ' > ul.images' );

            if ( image_preview.length == 0 ) return;

            image_preview = image_preview.first();

            attachments.map( function( attachment ){
                attachment = attachment.toJSON();

                if ( undefined != typeof attachment.id ) {
                    image_preview.prepend(
                        '<li data-id="' + attachment.id + '"' + 
                            ' style="background-image:url(' + attachment.url + ');">' +
                            '<a class="image-edit" href="#" onclick="AXiAdminWidget.imgSliderWidget.edit_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                                '<i class="dashicons dashicons-edit"></i>' +
                            '</a>' +
                            '<a class="image-delete" href="#" onclick="AXiAdminWidget.imgSliderWidget.remove_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                                '<i class="dashicons dashicons-trash"></i>' +
                            '</a>' +
                        '</li>'
                    );
                }
            } );

            image_preview.sortable({
                items: "> *:not(:last-child)",
                stop: function( event, ui ) {
                    _this.generate_values( widget_id );
                }
            });

            _this.generate_values( widget_id );
        },

        render_changed_image: function( widget_id, attachment, image_id ) {
            var item = $( '#' + widget_id + ' li[data-id="' + image_id + '"]' );
            if ( undefined != typeof attachment.id ) {
                item.html(
                    '<a class="image-edit" href="#" onclick="AXiAdminWidget.imgSliderWidget.edit_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                        '<i class="dashicons dashicons-edit"></i>' +
                    '</a>' +
                    '<a class="image-delete" href="#" onclick="AXiAdminWidget.imgSliderWidget.remove_image(event,\'' + widget_id + '\',' + attachment.id + ')">' +
                        '<i class="dashicons dashicons-trash"></i>' +
                    '</a>'
                );
                item.attr( 'data-id', attachment.id );
                item.css({
                    'background-image': 'url(' + attachment.url + ')'
                });
            }
        },

        edit_image: function( event, widget_id, image_id ) {
            event.preventDefault();
            var _this = this;
            var frame = wp.media({
                className: 'media-frame axisys-media-frame',
                title : imgSliderWidget.AXiAdminLocalize.frame_edit_title,
                multiple : false,
                library : { type : 'image' },
                button : { text : imgSliderWidget.AXiAdminLocalize.button_edit_title }
            });

            frame.off( 'open' ).on( 'open',function() {
                var selection = frame.state().get('selection');
                var attachment = wp.media.attachment( image_id );
                selection.add( attachment ? attachment : '' );
            } );

            frame.off( 'close' ).on( 'close', function() {
                var attachment = frame.state().get( 'selection' ).first().toJSON();
                _this.render_changed_image( widget_id, attachment, image_id );
            });

            frame.open();
        },

        remove_image: function( event, widget_id, image_id ) {
            event.preventDefault();
            $( '#' + widget_id + ' li[data-id="' + image_id + '"]' ).remove();
            this.generate_values( widget_id );
        },

        generate_values: function( widget_id ) {
            var image_previews = $( '#' + widget_id + ' > ul.images > li' ),
                image_ids = [],
                field_value = $( '#' + widget_id + 'images' );

            image_previews.each( function() {
                var image_id = $(this).data('id');
                if ( undefined != image_id && ! isNaN( image_id ) && image_id != 0 ) {
                    image_ids.push( image_id );
                }
            } );
            
            if ( image_ids.length > 0 ) {
                field_value.val( image_ids.join( "," ) );
            }
        }
    }

    document.addEventListener( 'DOMContentLoaded', function() {
        if ( 'undefined' !== typeof $.fn.select2 ) {
            AXiAdmin.fnSelect2();
        }
        AXiAdmin.fnAdminMenu();
        AXiAdmin.fnCourseMetaBoxes();
        AXiAdmin.fnPageMetaBoxes();
        // AXiAdmin.fnPromotionMetaBoxes();
        AXiAdmin.fnACFPromotion();
        AXiAdmin.fnDisciplineLinkMetaBoxes();

        if ( 'undefined' == typeof window.AXiAdminWidget ) {
            window.AXiAdminWidget = AXiAdminWidget;
        }

        if ( 'undefined' !== typeof window.wp.data ) {
            wp.data.dispatch( 'core/edit-post' ).removeEditorPanel( 'taxonomy-panel-axi_discipline' );
            wp.data.dispatch( 'core/edit-post' ).removeEditorPanel( 'taxonomy-panel-axi_discipline_guide' );
            wp.data.dispatch( 'core/edit-post' ).removeEditorPanel( 'taxonomy-panel-axi_discipline_link' );
            wp.data.dispatch( 'core/edit-post' ).removeEditorPanel( 'taxonomy-panel-axi_delivery_mode' );
            wp.data.dispatch( 'core/edit-post' ).removeEditorPanel( 'taxonomy-panel-axi_course_type' );
            wp.data.dispatch( 'core/edit-post' ).removeEditorPanel( 'taxonomy-panel-axi_location' );
        }
    }, false );
})( jQuery );