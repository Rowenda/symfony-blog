function onSubmitCommentForm(event) {
    event.preventDefault();

    const data = new FormData(event.currentTarget);

    const url = new URL(window.location.href);

    fetch(`${url.pathname}/add-comment`, {
            method: 'post',
            body: data,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            const container = document.querySelector('.comments-list');
            container.innerHTML = html + container.innerHTML;

            document.getElementById('comment-form').reset();

            $('#add-comment-success-modal').modal('show')
        });
}

/**
 * **********************************************************************
 * Code Principal *******************************************************
 * **********************************************************************
 */

jQuery(document).ready(function($) {

    $('#comment-form').on('submit', onSubmitCommentForm);
});