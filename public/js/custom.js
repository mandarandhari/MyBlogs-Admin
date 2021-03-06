$(document).ready(() => {
    $(document).on('change', '#banner', (event) => {
        let file = event.target.files[0];
        let reader = new FileReader();

        reader.onloadend = () => {
            $('#bannerBase64 img').attr('src', reader.result);
            $('#bannerBase64').show();
        }

        reader.readAsDataURL(file);
    });

    $(document).on('change', '#thumb', (event) => {
        let file = event.target.files[0];
        let reader = new FileReader();

        reader.onloadend = () => {
            $('#thumbBase64 img').attr('src', reader.result);
            $('#thumbBase64').show();
        }

        reader.readAsDataURL(file);
    });

    $(document).on('click', '.delete-article-btn', function () {
        $('.delete-article-form').attr('action', $(this).attr('data-url')); 
    });

    $(document).on('click', '.delete-user-btn', function () {
        $('.delete-user-form').attr('action', $(this).attr('data-url')); 
    });

    $(document).on('click', '.delete-customer-btn', function () {
        $('.delete-customer-form').attr('action', $(this).attr('data-url')); 
    });

    $(document).on('click', '.delete-contact-btn', function () {
        $('.delete-contact-form').attr('action', $(this).attr('data-url')); 
    });

    $(document).on('click', '.delete-comment-btn', function () {
        $('.delete-comment-form').attr('action', $(this).attr('data-url')); 
    });

    $(document).on('click', '.view-contact-btn', function() {
        $('.contact-name').html($(this).attr('data-name'));
        $('.contact-email').html($(this).attr('data-email'));
        $('.contact-phone').html($(this).attr('data-phone'));
        $('.contact-message').html($(this).attr('data-message'));
    });

    $(document).on('click', '.submit-btn', function() {
        $(this).html('<i class="fa fa-spinner fa-spin"></i> Please Wait');
        $(this).attr('disabled', 'disabled');
        $(this).parents('form:first').submit();
    });
});