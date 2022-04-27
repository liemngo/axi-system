;( function( elementor, $ ) {
    "use strict";

    var aXiSortableSelect = elementor.modules.controls.BaseData.extend( {
        onReady: function onReady() {
            var _this = this,
                _wrapper = this.ui.input.parent(),
                _thisV = [];

            this.initialized = true;
            this.selectEl  = _wrapper.children( '[data-axiec-sortable-select-role="select"]' );
            this.displayEl = _wrapper.children( '[data-axiec-sortable-select-role="display"]' );

            $( 'option[value="0"]', _this.selectEl ).attr( 'disabled', true );

            if ( ! this.displayEl.length ) {
                this.displayEl = $( '<ul class="axiec-sortable-select-display" data-axiec-sortable-select-role="display"></ul>' );
                _wrapper.prepend( this.displayEl );
            }

            this.selectEl.on( 'change', function() {
                var _select = this;
                if ( _select.value ) {
                    var $liEl = $(
                        '<li data-axiec-value="' + _select.value + '">' +
                            '<a href="javascript:void(0)" class="remove">×</a>' +
                            '<span class="text">' + _select.options[ _select.selectedIndex ].text + '</span>' +
                        '</li>'
                    );
                    $liEl.on( 'click', '> a', function() {
                        var $_a = $( this );
                        var liElValue = $_a.parent().data( 'axiec-value' );
                        $( 'option[value="' + liElValue + '"]', _this.selectEl ).attr( 'disabled', false );
                        $_a.parent().remove();
                        _this.axiRender( _this );
                    });
                    $( 'option[value="' + _select.value + '"]', _this.selectEl ).attr( 'disabled', true );
                    _this.displayEl.append( $liEl );
                    _this.axiRender( _this );
                }
                _select.value = 0;
            });

            $( '>li[data-axiec-value] > a', this.displayEl ).on( 'click', function() {
                var $_a = $( this );
                var liElValue = $_a.parent().data( 'axiec-value' );
                $( 'option[value="' + liElValue + '"]', _this.selectEl ).attr( 'disabled', false );
                $_a.parent().remove();
                _this.axiRender( _this );
            });
        },
        axiRender: function axiRender( _this ) {
            _this.axiSetInputValues( _this );
        },
        axiSetInputValues: function axiSetInputValues( _this ) {
            var ids = [];
            _this.displayEl.children().each( function() {
                var li_id = $( this ).data('axiec-value');
                if ( undefined !== li_id && ! isNaN( li_id ) && li_id !== 0 ) {
                    ids.push( li_id );
                }
            } );
            if ( ids.length > 0 ) {
                _this.ui.input.val( ids.join( "," ) );
            }
            else {
                _this.ui.input.val( '' );
            }
            _this.ui.input.trigger( 'input' );
        },
        onBeforeDestroy: function onBeforeDestroy() {
            this.displayEl.remove();
            this.onReady();
            // this.ui.input.val( '' );
            // this.$el.remove();
        },
        // onAfterExternalChange: function onAfterExternalChange() {}
    });

    elementor.addControlView( 'axi_sortable_select', aXiSortableSelect );
})( elementor, jQuery );
