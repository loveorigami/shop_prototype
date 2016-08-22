jQuery(function() {
    
    function SendRequest() {
        var self = this;
        SendRequestAbstract.apply(this, arguments);
        this._allText = '------------------------';
        var parentSuccess = this.success;
        this.success = function(data, status, jqXHR) {
            parentSuccess.apply(this, arguments);
            $('#' + self._formID).find('#productsmodel-id_subcategory').find('option:first-child').attr('disabled', true);
        };
        
    };
    
    $('select#productsmodel-id_categories').change(function(event) {
        (new SendRequest()).send(event);
    });
    
});
