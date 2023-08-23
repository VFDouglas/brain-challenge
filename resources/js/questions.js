document.getElementById('form_answer_question').addEventListener('submit', function (event) {
    event.preventDefault();

    let options = {
        method : 'POST',
        headers: window.ajaxHeaders,
        body   : JSON.stringify({
            option_id: document.querySelector('.option_radio:checked').getAttribute('data-option_id')
        })
    }

    fetch(`./answerQuestion`, options).then(function (response) {
        if (!response.ok) {
            window.modalMessage({
                title      : 'Answer',
                description: 'Error answering the question',
                type       : 'error'
            });
            return false;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.error) {
                window.modalMessage({
                    title      : 'Answer',
                    description: jsonResponse.error,
                    type       : 'error'
                });
            } else {
                window.modalMessage({
                    title      : 'Answer',
                    description: 'Answer saved.',
                    type       : 'success'
                });
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
            }
        });
    });
});
