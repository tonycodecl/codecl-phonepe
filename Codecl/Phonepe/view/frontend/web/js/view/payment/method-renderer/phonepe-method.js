/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'Magento_Checkout/js/view/payment/default',
        'mage/url'
    ],
    function (Component, url) {
        'use strict';

        return Component.extend(
            {
                defaults: {
                    template: 'Codecl_Phonepe/payment/phonepe'
                },
                redirectAfterPlaceOrder: false,
                /**
                 * After place order callback
                 */
                afterPlaceOrder: function () {
                    jQuery(
                        function ($) {
                                $('.phonepe .actions-toolbar .checkout').prop('disabled', true);
                            $.ajax(
                                {
                                    url: url.build('phonepe/payment/start'),
                                    type: 'get',
                                    dataType: 'json',
                                    cache: false,
                                    processData: false, // Don't process the files
                                    contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                                    success: function (data) { 
                         
                                        var response = JSON.parse(data);

                                        if(response.success == true) {
                                                     var url = response.data.instrumentResponse.redirectInfo.url;
                                                     window.location.href = url;
                                                     return true;    
                                        }
                                        else {
                                                      var message = response.message;
                           
                                                      $(".payment-method-content #phonepe").html('<span class="payment-error-message" style="color:red;">'+message+'</span>'); 
                                            setTimeout(
                                                function () {
                                                    $('.payment-method-content #phonepe .payment-error-message').fadeOut('slow');
                                                    $(".payment-method-content #phonepe .payment-error-message").remove();
                                                }, 2000
                                            );
                                                          $('.phonepe .actions-toolbar .checkout').prop('disabled', false);
                                        }
                                    },
                                    error: function (xhr, ajaxOptions, thrownError) {
                                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                                    }
                                }
                            );
                        }
                    );
                }
            }
        );
    }
);
