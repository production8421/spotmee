<script src="{{ $cubaAsset('js/jquery.min.js') }}"></script>
<script src="{{ $cubaAsset('js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ $cubaAsset('js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ $cubaAsset('js/icons/feather-icon/feather-icon.js') }}"></script>
<script src="{{ $cubaAsset('js/config.js') }}"></script>
<script src="{{ $cubaAsset('js/script.js') }}"></script>
<script src="{{ $cubaAsset('js/script1.js') }}"></script>
<script>
    (function ($) {
        'use strict';

        // Cuba theme expects input[name="login[password]"]; Laravel auth forms use name="password".
        $('.show-hide span').off('click').on('click', function () {
            var $input = $(this).closest('.form-input').find('input');
            if ($(this).hasClass('show')) {
                $input.attr('type', 'text');
                $(this).removeClass('show');
            } else {
                $input.attr('type', 'password');
                $(this).addClass('show');
            }
        });

        $('form button[type="submit"]').on('click', function () {
            var $form = $(this).closest('form');
            $form.find('.form-input').has('.show-hide').each(function () {
                $(this).find('input').attr('type', 'password');
                $(this).find('.show-hide span').addClass('show');
            });
        });
    })(jQuery);
</script>
