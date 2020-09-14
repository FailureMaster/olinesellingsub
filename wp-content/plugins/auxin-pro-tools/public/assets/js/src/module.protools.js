;(function($){

    // on document ready
    $(function(){

        // Auxin Countdown Element
        $.fn.AuxinCountDown = function(){
            var $wrapper = $(this).find('.aux-countdown-wrapper '),
                data = {
                    year     : $wrapper.data('countdown-year'),
                    month    : $wrapper.data('countdown-month'),
                    day      : $wrapper.data('countdown-day'),
                    hour     : $wrapper.data('countdown-hour'),
                    min      : $wrapper.data('countdown-min'),
                    sec      : $wrapper.data('countdown-sec')
                },

                targetDate = new Date(data.year,data.month,data.day,data.hour,data.min,data.sec);
                $year      = $wrapper.find('.aux-countdown-year'),
                $month     = $wrapper.find('.aux-countdown-month'),
                $day       = $wrapper.find('.aux-countdown-day'),
                $hour      = $wrapper.find('.aux-countdown-hour'),
                $min       = $wrapper.find('.aux-countdown-min'),
                $sec       = $wrapper.find('.aux-countdown-sec');
                
                setInterval( function(){
                    var diffTime = ( Date.parse(targetDate) - Date.parse(new Date() ) ) / 1000;
                    
                    if ( diffTime < 0 ) return;
                    
                    $year.text( Math.floor( diffTime /  ( 31536000 ) ) ); // 1 year = 3153600 second
                    $month.text( Math.floor( ( diffTime / 2592000 ) % 12 ) ); // 1 month = 2592000 second
                    $day.text( Math.floor( ( diffTime / 86400 ) % 365 ) ); // 1 day = 86400 second
                    $hour.text( Math.floor( ( diffTime / 3600 ) % 24 ) ); // 1 hour = 3600 second
                    $min.text( Math.floor( ( diffTime / 60 ) % 60 ) ); // 1 min  = 60 second
                    $sec.text( Math.floor( ( diffTime ) % 60 ) );

            },1000 )
            
        }

        $('.aux-widget-countdown').each(function(){
            $(this).AuxinCountDown();
        });

        $.fn.AuxinCounter = function( $scope ) {
            $scope = $scope || $(this);
            $scope.find('.aux-counter-number').each(function(){
                var $this = $(this),
                    hasAnimation = $('body').hasClass('aux-page-animation');
                var data = {
                    delay: $this.data('delay'),
                    easing: $this.data('easing'),
                    duration: $this.data('duration'),
                    toValue: $this.data('to-value'),
                    delimiter: $this.data('delimiter')
                };
    
                var run_counter = function( $this, data ) {
                    if ( $.fn.appearl ) {
                        $this.appearl().one( 'appear', function(){        
                            if ( data.delay ) {
                                setTimeout( function(){
                                    $this.numerator( data );
                                }, data.delay );
                            } else {
                                $this.numerator( data );   
                            }
                        });
                    } else {
                        if ( data.delay ) {
                            setTimeout( function(){
                                $this.numerator( data );
                            }, data.delay );
                        } else {
                            $this.numerator( data );   
                        }
                    }
                }
    
                if ( hasAnimation ) {
                    document.body.addEventListener( 'AuxPageAnimationDone', function(event) {
                        run_counter( $this, data );    
                    });
                } else {
                    run_counter( $this, data );
                }
            });
        }

        $.fn.AuxinCounter( $('body') );

    });

})(jQuery);