document.getElementById("generate-questions").addEventListener("click", function () {
    const choiceQuestionsCount = parseInt(document.getElementById("choice-questions").value) || 0;
    const trueFalseQuestionsCount = parseInt(document.getElementById("true-false-questions").value) || 0;
    const photoQuestionsCount = parseInt(document.getElementById("photo-questions").value) || 0;
    const questionsContainer = document.getElementById("generated-questions");
    questionsContainer.innerHTML = ""; // Clear previous questions

    // Generate Choice Questions
    for (let i = 0; i < choiceQuestionsCount; i++) {
        const questionDiv = document.createElement("div");
        questionDiv.className = "question-section";
        questionDiv.innerHTML = `
                <h3>Choice Question ${i + 1}</h3>
                <label>Question Title:</label>
                <input type="text" name="questions[${i}][question_text]" required>
                <input type="hidden" name="questions[${i}][question_type]" value="multiple_choice">

                <label>Choice 1:</label>
                <input type="text" name="questions[${i}][answers][0][answer_text]" required>

                <label>Choice 2:</label>
                <input type="text" name="questions[${i}][answers][1][answer_text]" required>

                <label>Choice 3:</label>
                <input type="text" name="questions[${i}][answers][2][answer_text]" required>

                <label>Choice 4:</label>
                <input type="text" name="questions[${i}][answers][3][answer_text]" required>

                <label>Correct Choice:</label>
                <select name="questions[${i}][correct_answer]" required>
                    <option value="0">Choice 1</option>
                    <option value="1">Choice 2</option>
                    <option value="2">Choice 3</option>
                    <option value="3">Choice 4</option>
                </select>
            `;
        questionsContainer.appendChild(questionDiv);
    }

    // Generate True/False Questions
    for (let i = 0; i < trueFalseQuestionsCount; i++) {
        const questionIndex = choiceQuestionsCount + i;
        const questionDiv = document.createElement("div");
        questionDiv.className = "question-section";
        questionDiv.innerHTML = `
                <h3>True/False Question ${i + 1}</h3>
                <label>Question Title:</label>
                <input type="text" name="questions[${questionIndex}][question_text]" required>
                <input type="hidden" name="questions[${questionIndex}][question_type]" value="true_false">

                <label>Correct Answer:</label>
                <select name="questions[${questionIndex}][correct_answer]" required>
                    <option value="true">True</option>
                    <option value="false">False</option>
                </select>
            `;
        questionsContainer.appendChild(questionDiv);
    }

    // Generate Photo Questions
    for (let i = 0; i < photoQuestionsCount; i++) {
        const questionIndex = choiceQuestionsCount + trueFalseQuestionsCount + i;
        const questionDiv = document.createElement("div");
        questionDiv.className = "question-section";
        questionDiv.innerHTML = `
                <h3>Photo Question ${i + 1}</h3>
                <label>Upload Image:</label>
                <input type="file" name="questions[${questionIndex}][photo]" accept="image/*" required>
                <input type="hidden" name="questions[${questionIndex}][question_type]" value="photo">

                <label>Question Title:</label>
                <input type="text" name="questions[${questionIndex}][question_text]" required>

                <label>Choice 1:</label>
                <input type="text" name="questions[${questionIndex}][answers][0][answer_text]" required>

                <label>Choice 2:</label>
                <input type="text" name="questions[${questionIndex}][answers][1][answer_text]" required>

                <label>Choice 3:</label>
                <input type="text" name="questions[${questionIndex}][answers][2][answer_text]" required>

                <label>Choice 4:</label>
                <input type="text" name="questions[${questionIndex}][answers][3][answer_text]" required>

                <label>Correct Choice:</label>
                <select name="questions[${questionIndex}][correct_answer]" required>
                    <option value="0">Choice 1</option>
                    <option value="1">Choice 2</option>
                    <option value="2">Choice 3</option>
                    <option value="3">Choice 4</option>
                </select>
            `;
        questionsContainer.appendChild(questionDiv);
    }
});
