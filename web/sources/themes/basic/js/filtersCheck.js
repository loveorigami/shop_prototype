function FiltersCheck()
{
    this.run = function() {
        try {
            $('#sidebar').on('click', '.products-filters-item', function(event) {
                var target = $(event.target);
                var ul = target.closest('ul');
                
                if (ul.hasClass('products-filters-sorting-field')) {
                    ul.find('.products-filters-item').removeClass('checked');
                }
                target.toggleClass('checked');
                
                var formItem = ul.data('form-item');
                var itemId = target.closest('li').data('id');
                
                if (formItem == 'filtersform-sortingfield') {
                    var field = $('#filtersform-sortingfield');
                    field.find('option').removeAttr('selected');
                    field.find('option[value="' + itemId + '"]').attr('selected', 1);
                } else {
                    var field = $('#' + formItem).find('input[value="' + itemId + '"]');
                    if (field.attr('checked') == 'checked') {
                        field.removeAttr('checked');
                    } else {
                        field.attr('checked', 1);
                    }
                }
            });
            
            $('#sidebar').on('click', '#filters-apply', function(event) {
                $('#products-filters-form').submit();
            });
            
            $('#sidebar').on('click', '#filters-cancel', function(event) {
                $('#products-filters-clean').submit();
            });
            
            this.mark();
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
    
    this.mark = function() {
        try {
            var sortingfield = $('#filtersform-sortingfield').find('option[selected]').val();
            $('.products-filters-sorting-field').find('li[data-id="' + sortingfield + '"]').children('.products-filters-item').addClass('checked');
            
            var colors = $('#filtersform-colors').find('input[checked]');
            colors.each(function(index, elm) {
                var colorfield = $(elm).val();
                $('.products-filters-colors').find('li[data-id="' + colorfield + '"]').children('.products-filters-item').addClass('checked');
            });
            
            var sizes = $('#filtersform-sizes').find('input[checked]');
            sizes.each(function(index, elm) {
                var sizefield = $(elm).val();
                $('.products-filters-sizes').find('li[data-id="' + sizefield + '"]').children('.products-filters-item').addClass('checked');
            });
        } catch (e) {
            console.log(e.name + ': ' + e.message);
        }
    };
};

 (new FiltersCheck()).run();
