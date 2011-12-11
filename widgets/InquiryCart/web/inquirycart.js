



var InquiryCart = function(options) {
    this.options = options || { };
    this.items = [];
};

InquiryCart.prototype = {

    init: function() { 
        var that = this;

        if( typeof $.jGrowl == 'undefined' ) 
            alert( 'please include $.jGrowl' );

        // register bottom-right jgrowl container

        this.growl_container = $('<div/>').addClass('jGrowl bottom-right').attr('id','jgrowl-box');
        $(document.body).append( this.growl_container );

        // register fixed position inquery cart

        this.cartbar = $('<div/>').addClass('inquirycart-bar');
        $(document.body).append( this.cartbar );

        this.title = $('<div/>').addClass('title').html(_('Inquiry Cart'));
        this.cartbar.append( this.title );

        this.desc = $('<div/>').addClass('desc');
        this.cartbar.append(this.desc);

        var linkwrapper = $('<div/>').addClass('link');
        var link = $('<a/>');
        link.attr({ href: this.options.submit_link }).html(_('Submit inquiry form'));
        linkwrapper.append( link );

        var clear_link = $('<a/>');
        clear_link.click(function() {
            if( confirm( _('Are you sure to clear inquiry form ?')) ) {
                that.clear();
            }
        });
        clear_link.html( _('Clear') );
        linkwrapper.append( ' , ' );
        linkwrapper.append( clear_link );

        this.cartbar.append( linkwrapper );

        $.getJSON('/api/inquirycart/list', function(data) { 
            var items = data.items;
            that.updateItemInfo(items);
        });
    },

    updateItemInfo: function(items) {
        var  size = items.length;
        this.items = items;
        this.desc.html( '<span class="number">' + size + '</span>' +  (size > 1 ? _(' items') : _(' item') ));
    },

    /* add product item */
    addItem: function(id) {
        var that = this;
        $.getJSON('/api/inquirycart/add_product/' + id , function(data) {
            // update user interface
            if( data.success ) {
                that.growl_container.jGrowl(data.message);
                that.updateItemInfo( data.items );
            }

        });
    },
    clear: function() {
        var that = this;
        $.getJSON('/api/inquirycart/clear', function(data) { 
            if( data.success ) {
                that.growl_container.jGrowl(data.message);
            }
        });
    }

};


InquiryCart.get = function(options) { 
    if( typeof __cart != 'undefined' )
        return __cart;
    __cart = new InquiryCart(options);
    __cart.init();
    return __cart;
};
