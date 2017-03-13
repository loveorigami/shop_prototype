$(function() {
    
    function Send() {
        AbstractSendForm.call(this);
    };
    
    Send.prototype = Object.create(AbstractSendForm.prototype);
    
    /* Отправляет форму с данными для очистки корзины, 
    * обновляет информацию и состоянии
    */
    $('div.shortCart').on('click', '#clean-cart-form > input:submit', function(event) {
        (new Send()).htmlSend(event, 'div.shortCart');
        event.preventDefault();
    });
    
    /* Отправляет форму с данными для отмены рассылок, 
    * обновляет информацию и состоянии
    */
    $('#unsubscribe-form').on('click', 'input:submit', function(event) {
        (new Send()).htmlSend(event, 'div.unsubscribe');
        event.preventDefault();
    });
});
