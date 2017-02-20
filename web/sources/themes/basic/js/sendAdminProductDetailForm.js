$(function() {
    
    function SendAdminProductDetailForm() {
        var self = this;
        AbstractSendForm.apply(this, arguments);
        self.success = function(data, status, jqXHR)
        {
            self.form.find('div.help-block').html('');
            if (typeof data == 'string') {
                self.form.closest('li').find('div.admin-product-previous-data').toggleClass('disable');
                self.form.closest('li').append(data);
            } else if (typeof data == 'object' && data.length != 0) {
                for (var key in data) {
                    $('#' + key).closest('div.form-group').find('div.help-block').text(data[key]);
                }
            }
        };
    };
    
    $('li').on('click', 'form[id^="admin-product-detail-get-form-"], form[id^="admin-product-detail-delete-form-"] > input[type="submit"]', function(event) {
        (new SendAdminProductDetailForm()).send(event);
        event.preventDefault();
    });
    
});
