function checkVisibility() {
    const selectedOption = document.querySelector('input[name="visibility"]:checked');
    const passwordContainer = document.getElementById('password-container');
    const passwordInput = document.getElementById('password');
    const warn_pass = document.getElementById('warn-pass');



    if (selectedOption && selectedOption.value === 'private') {
        passwordContainer.style.display = 'block';
        passwordInput.required = true; // جعل الحقل مطلوبًا
        passwordInput.minLength = 8; // تحديد الحد الأدنى للطول ليكون 6

        if (passwordInput.value.length >= 8) {
            warn_pass.style.display = 'none';
        }
        // setInterval(() => {
        //     if (!passwordInput.required || passwordInput.minLength !== 8) {
        //         alert("Tampering attempt detected! Please do not modify field settings.");
        //         passwordInput.required = true;
        //         passwordInput.minLength = 8;
        //     }
        // }, 1000); // كل ثانية
    } else {
        passwordContainer.style.display = 'none';
        passwordInput.required = false; // إزالة الخاصية إذا كان الحقل غير ظاهر
        passwordInput.minLength = 0; // إعادة الحد الأدنى للطول إلى 0 عند الإخفاء
    }


}

// تنفيذ الفحص عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    checkVisibility(); // تحقق من حالة الخيار عند تحميل الصفحة

    const visibilityOptions = document.querySelectorAll('input[name="visibility"]');
    visibilityOptions.forEach(option => {
        option.addEventListener('change', checkVisibility);
    });
});


document.addEventListener("DOMContentLoaded", function() {
    // Add smooth transition for each form-group
    const formGroups = document.querySelectorAll(".form-group");
    formGroups.forEach(group => {
        group.style.transition = "all 0.3s ease-in-out";
    });

    // Toggle visibility of choices for multiple choice questions
    document.querySelectorAll("input[type='radio']").forEach(radio => {
        radio.addEventListener("change", (event) => {
            if (event.target.value === "multiple_choice") {
                event.target.closest(".form-group").querySelector(".choices-container")
                    .style.display = "block";
            } else {
                event.target.closest(".form-group").querySelector(".choices-container")
                    .style.display = "none";
            }
        });
    });
});
