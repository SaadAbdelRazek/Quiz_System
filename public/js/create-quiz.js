// إعداد Quill
var quill = new Quill('#editor-container', {
    theme: 'snow'
});

// حفظ المحتوى عند الإرسال
document.getElementById('quiz-form').onsubmit = function() {
    document.getElementById('quiz-description').value = quill.root.innerHTML;
};


const visibilityOptions = document.querySelectorAll('input[name="visibility"]');
visibilityOptions.forEach(option => {
    option.addEventListener('change', function() {
        const passwordContainer = document.getElementById('password-container');
        passwordContainer.style.display = this.value === 'private' ? 'block' : 'none';
    });
});
