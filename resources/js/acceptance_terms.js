document.getElementById('form_acceptance_terms').addEventListener('submit', function (event) {
    event.preventDefault();
    document.getElementById('spinner_accept_terms').classList.remove('d-none');

    let options = {
        headers: window.ajaxHeaders,
        method : 'POST'
    }
    fetch('./accept_terms', options).then(function (response) {
        if (!response.ok) {
            modalMessage({
                title      : 'Acceptance Terms',
                description: 'Error accepting the terms.',
                type       : 'error',
                time       : 2000
            });
            document.getElementById('spinner_accept_terms').classList.add('d-none');
            return;
        }
        response.json().then(function (jsonResponse) {
            if (jsonResponse.msg === 'success') {
                window.logAccess('Acceptance Terms', 'Terms accepted');
                setTimeout(() => {
                    window.location.replace('/');
                }, 1000);
            } else {
                window.logAccess('Acceptance Terms', 'Error accepting the terms');                modalMessage({
                    title      : 'Acceptance Terms',
                    description: jsonResponse.msg,
                    type       : 'error',
                    time       : 4500
                });
            }
        });
    });
});
window.logAccess('Acceptance Terms', 'Loaded page');
