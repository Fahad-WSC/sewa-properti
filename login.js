document.addEventListener('DOMContentLoaded', function() {
    const togglePassword = document.querySelector('#togglePassword');
    const password = document.querySelector('#loginPassword');

    if (togglePassword && password) {
        togglePassword.addEventListener('click', function () {
            
            if (password.getAttribute('type') === 'password') {
                password.setAttribute('type', 'text');
                this.textContent = 'HIDE';
            } else {
                password.setAttribute('type', 'password');
                this.textContent = 'SHOW';
            }

        });
    }
});