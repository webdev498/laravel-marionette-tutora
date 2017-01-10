define([
    'base',
    'underscore'
], function (
    Base,
    _
) {

    return Base.Controller.extend({
        initialize : function (options) {
            var arrowsSelector = '.scrollable--arrows--wrapper .arrow';
            var body = $('body');

            body.on('mousedown', arrowsSelector, _.bind(this.startScrolling, this));
            body.on('mouseup', arrowsSelector, _.bind(this.stopScrolling, this));
        },

        startScrolling: function(e) {
            var arrow = $(e.target);
            var arrowsTab = arrow.parent('.scrollable--arrows--wrapper');

            this.scrollable = arrowsTab.find('.tabs--scrollable');
            this.tabList = arrowsTab.find('.tabs__list');
            this.leftArrow = arrowsTab.find('.arrow--left');
            this.rightArrow = arrowsTab.find('.arrow--right');

            var isLeftArrowClicked = arrow.hasClass('arrow--left');

            if(isLeftArrowClicked) {
                this.timer = setInterval(_.bind(this.shiftTabToRight, this), 50);
            } else {
                this.timer = setInterval(_.bind(this.shiftTabToLeft, this), 50);
            }
        },

        stopScrolling: function() {
            clearInterval(this.timer);
        },

        shiftTabToLeft: function() {
            this.shiftTab("left");
        },

        shiftTabToRight: function() {
            this.shiftTab("right");
        },

        shiftTab: function(direction) {
            var scrollPosition = this.scrollable.scrollLeft();
            var leftPosition = this.tabList.offset().left;
            var rightPosition = leftPosition + this.tabList.outerWidth();
            var minRightPosition = this.rightArrow.offset().left;

            var step = 15;
            var leftDirection = direction === 'left';
            var rightDirection = direction === 'right';

            if((scrollPosition <= 0 && rightDirection) || (rightPosition <= minRightPosition && leftDirection)) {
                this.stopScrolling();
                return;
            }

            if(leftDirection){
                scrollPosition += step;
            } else {
                scrollPosition -= step;
            }

            this.scrollable.scrollLeft(scrollPosition);
        }
    });

});