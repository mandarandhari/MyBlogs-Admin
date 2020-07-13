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
});