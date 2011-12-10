



var InquiryCart = function() {
    this.items = [];
};

InquiryCart.prototype = {

    init: function() { 
        if( typeof $.jGrowl == 'undefined' ) 
            alert( 'please include $.jGrowl' );

        // register bottom-right jgrowl container

        this.growl_container = $('<div/>').addClass('jGrowl bottom-right').attr('id','jgrowl-box');
        $(document.body).append( this.growl_container );

        // register fixed position inquery cart

        this.cartbar = $('<div/>').addClass('inquirycart-bar');
        $(document.body).append( this.cartbar );

        this.title = $('<div/>').addClass('title').html('Inquiry Cart');
        this.cartbar.append( this.title );

        this.desc = $('<div/>').addClass('desc');
        this.cartbar.append(this.desc);

        var that = this;
        $.getJSON('/api/inquirycart/list', function(data) { 
            var items = data.items;
            that.updateItemInfo(items);
        });
    },

    updateItemInfo: function(items) {
        var  size = items.length;
        this.items = items;
        this.desc.html( '<span class="number">' + size + '</span>' +  (size > 1 ? ' items' : ' item' ));
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
    }

};


InquiryCart.get = function() { 
    if( typeof __cart != 'undefined' )
        return __cart;
    __cart = new InquiryCart;
    __cart.init();
    return __cart;
};


// depends on jGrowl
$(document.body).ready(function() {
    var cart = InquiryCart.get();
});
