( function( $ ) {
    var $win = $( window );
    /**
     * @param $scope The Widget wrapper element as a jQuery element
     * @param $ The jQuery alias
     */
    var fnAccordionWidgetsHandler = function( $scope, $ ) {
        $win.trigger( 'axi-accordion-update', { $scope: $scope } );
    };

    var fnCarouselWidgetsHandler = function( $scope, $ ) {
        $win.trigger( 'axi-carousel-update', { $scope: $scope } );
    };

    var fnCDLButtonWidgetsHandler = function( $scope, $ ) {
        $win.trigger( 'axi-cdlbutton-update', { $scope: $scope } );
    }

    var fnComparisonTableWidgetsHandler = function( $scope, $ ) {
        $win.trigger( 'axi-comparison-table-update', { $scope: $scope } );
    }

    var fnCourseListWidgetsHandler = function( $scope, $ ) {
        $win.trigger( 'axi-course-list-update', { $scope: $scope } );
    }

    var fnDisciplineListWidgetsHandler = function( $scope, $ ) {
        $win.trigger( 'axi-discipline-list-update', { $scope: $scope } );
    }

    var fnMCoursesWidgetsHandler = function( $scope, $ ) {
        $win.trigger( 'axi-mcourses-update', { $scope: $scope } );
    }

    var fnMediaCardsWidgetsHandler = function( $scope, $ ) {
        $win.trigger( 'axi-media-cards-update', { $scope: $scope } );
    };

    function fnNavMenuAsideWidgetsHandler( $scope, $ ) {
        $win.trigger( 'axi-navmenu-aside-update', { $scope: $scope } );
    }

    var fnGetValueWidgetsHandler = function( $scope, $ ) {
        $win.trigger( 'axi-getvalue-update', { $scope: $scope } );
    };

    var fnStudyModesWidgetsHandler = function( $scope, $ ) {
        $win.trigger( 'axi-study-modes-update', { $scope: $scope } );
    }

    // Make sure you run this code under Elementor.
    $win.on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-accordion.default', fnAccordionWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-banner-carousel.default', fnCarouselWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-organisations.default', fnCarouselWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-feedbacks.default', fnCarouselWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-instructors.default', fnCarouselWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-cdlbutton.default', fnCDLButtonWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-comparison-table.default', fnComparisonTableWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-course-list.default', fnCourseListWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-discipline-list.default', fnDisciplineListWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-mcourses.default', fnMCoursesWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-media-cards.default', fnMediaCardsWidgetsHandler )
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-navmenu-aside.default', fnNavMenuAsideWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-navmenu-side.default', fnGetValueWidgetsHandler );
        elementorFrontend.hooks.addAction( 'frontend/element_ready/axi-study-modes.default', fnStudyModesWidgetsHandler );
    } );
} )( jQuery );