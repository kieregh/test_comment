/**
 * Copyright (c) 2015 kiere@navercom
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.0.0
 */
(function ($) {
    $.fn.RbComments= function (options) {

        var defaults = {
            placeholder: "What's in your mind",
            imageQuantity: -1 // illimited
        };

        var opts = jQuery.extend(defaults, options);

        function trim(str) {
            return str.replace(/^\s+|\s+$/g, "");
        }

        var selector = $(this).selector;
        selector = selector.substr(1);
        
        // 추가 데이타를 배열로 넘긴 후 opts 를 통해서 치환하면 적용된다. 
        var loader=opts.themeUrl+'/img/ajax-loader.gif';
        var themeName=opts.themeName;
    
        var regisComment = function () {

            $.post(rooturl+'/?m=comment&a=start_link_preview&theme='+themeName, {
                 text: text,
                 imagequantity: opts.imageQuantity
             }, function (answer) {
                      
             }
            
        };

    };

})(jQuery);
