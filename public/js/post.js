function onSubmitCommentForm(event) {
    event.preventDefault();

    const data = new FormData(event.currentTarget);

    const url = new URL(window.location.href);

    fetch(url.pathname, {
            method: 'post',
            body: data,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(data => console.log(data));
}

/**
 * **********************************************************************
 * Code Principal *******************************************************
 * **********************************************************************
 */

jQuery(document).ready(function($) {

    $('#comment-form').on('submit', onSubmitCommentForm);
});