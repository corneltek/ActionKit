


var StepHint = function(stepData,opts) {
    this.stepData = stepData;
    this.current  = -1;
    this.opts  = $.extend( { 
        stepShow: true,
        stepText: "Step ",
        nextLink: true,
        nextLinkText: 'Next Step',
        skipNotFound: true,
        cycleKeepLast: true,
        cycle: 3000,
        topPadding: 100,
        leftPadding: 20,
        scrollDuration: 1100
    }, opts);

    if( this.opts.stepShow ) {
        for( var i in this.stepData ) {
            var s = this.stepData[i];
            s.step = (parseInt(i) + 1); // step number, not index
        }
    }
};

StepHint.prototype = {

    reset: function() {
        this.current = -1;
    },

    hasNext: function() {
        return this.stepData[ this.current + 1 ];
    },

    getNextStep: function() { 
        return this.stepData[ ++this.current ];
    },

    getPrevStep: function() {
        return this.stepData[ --this.current ];
    },

    hasPrevStep: function() { return this.stepData[ this.current - 1 ]; },

    getCurrentStep: function() {
        return this.stepData[ this.current ];
    },

    reveal: function() {
        var current = this.getCurrentStep();
        var attached = this.attachHint(current);
    },

    next: function() { 
        // hide current
        var current = this.getCurrentStep();
        if( current && current.el ) {
            current.el.fadeOut();
        }

        if( this.hasNext() ) {
            var data = this.getNextStep();
            var attached = this.attachHint(data);
            if( ! attached ) {
                this.next();
            }
        }

        return this;
    },


    clearTimer: function() {
        if( this._c ) {
            // console.log( 'clear timer' );
            window.clearInterval( this._c );
            this._c = null;
        }
    },


    toggleCycle: function() {
        if( this.inCycle() ) {
            // console.log('un-cycle');
            this.clearTimer();
            this.clear();
            this.reset();
        } else {
            // console.log('cycle');
            this.reset();
            this.next().cycle();
        }
    },

    cycle: function() {
        var that = this;
        this.clearTimer();

        this._c = window.setInterval( function() { 
            if( that.hasNext() ) {
                that.next();
            } else {
                if( ! that.opts.cycleKeepLast )
                    that.clear();
                that.clearTimer();
            }
        } , this.opts.cycle );
    },

    inCycle: function() { return this._c; },

    prev: function() { 
          
          
    },

    all: function() {   },

    clear: function() { 
        $(this.stepData).each(function(i,e) {
            if( e.el ) {
                e.el.hide();
            }
        });
    },

    renderHint: function(data) {

        /*
        <div class="hint">
            <div class="hint-arrow"> </div>
            <div class="hint-modal">
                <div class="hint-step">Step 3</div>

                <div class="hint-title">Title</div>
                <div class="hint-text">
                    Hello World, how to submit a new story? 
                    Please click here.
                    blabhbahbhabhah bahahb
                </div>
                <div style="clear: both;" ></div>
            </div>
        </div>
        */
        var dHint = $('<div/>').addClass('hint');

        var dArrow = $('<div/>').addClass('hint-arrow');
        dHint.append( dArrow );

        var dModal = $('<div/>').addClass('hint-modal');

        var that = this;
        var closebtn = $('<div>').addClass('hint-closebtn');
        closebtn.click( function() {
            dHint.fadeOut();
            that.clearTimer();
        });

        dModal.append( closebtn );


        if( data.width ) {
            dModal.width( data.width );
        }


        if( data.step ) {
            var dStep  = $('<div/>').addClass('hint-step').html( this.opts.stepText + data.step );
            dModal.append( dStep );
        }

        if( data.title ) {
            var dTitle = $('<div/>').addClass('hint-title').html( data.title );
            dModal.append( dTitle );
        }

        if( data.ajax || data.text || data.html ) {
            var dText = $('<div/>').addClass('hint-text');
            if( data.text )
                dText.text( data.text );
            if( data.html )
                dText.html( data.html );


            if( data.ajax ) {
                $.get( data.ajax , function(data) {
                    dText.html( data );
                });
            }

            dModal.append( dText );
        }

        if( this.opts.nextLink && this.hasNext() ) {
            var that = this;
            var link = $('<a/>').addClass('hint-link hint-next-link');
            link.html( this.opts.nextLinkText );
            link.attr( 'href' , '#' );
            link.click( function() {
                that.next();

                if( that.inCycle() )
                    that.cycle();

                return false;
            });
            dModal.append( $('<div/>').css({ textAlign: 'right' }).append( link ) );
        }

        dHint.append( dModal );
        return dHint;
    },
    showHint: function(data) {
        this.attachHint(data);
    },

    scroll: function(data) {
        // scrollTo
        if( $.scrollTo ) {

            var topPadding = this.opts.topPadding;
            var leftPadding = this.opts.leftPadding;
            if( data.position ) {
                /* work around, offset option doesn't work */
                $.scrollTo( {
                    left: data.position.left - leftPadding,
                    top: data.position.top - topPadding
                } , this.opts.scrollDuration , opts );
            }
            else if( data.below ) {
                var opts = { offset: { top: - topPadding , left: - leftPadding } };
                var e = $(data.below);
                if( e.get(0) )
                    $.scrollTo(e, this.opts.scrollDuration , opts);
            }
        }
    },

    attachHint: function(data) { 
        var h;
        if( data.el )
            h = data.el;
        else {
            // create new element and append to document.body.
            var h = this.renderHint(data);
            h.hide();
            $(document.body).append(h);

            // register element to data
            data.el = h;
        }

        if( data.below ) {
            var e;
            if( typeof data.below == "string" ) {
                e = $(data.below);

                if( e.get(0) == null) {
                    if( this.opts.skipNotFound ) {
                        return false;
                    } else {
                        if( window.console )
                            console.error( "Element of " + data.below + " not found." );
                    }
                }

            } else {
                e = data.below;
            }

            // var pos = e.position();
            var pos = e.offset();
            var y = pos.top + e.height();
            var x = pos.left + 10;
            h.css({
                position: 'absolute',
                left: x,
                top: y
            });
        }
        else if ( data.position ) {
            h.css({ 
                position: 'absolute',
                left: data.position.left,
                top: data.position.top
            });
        }

        h.fadeIn('slow');

        this.scroll( data );
        return true;
    }
};


/*
h1.next();
h1.next();
h1.next(); // when empty, clear all.
h1.prev(); // previous step

h1.all(); // show all hints
h1.clear(); // remove all hints
*/
