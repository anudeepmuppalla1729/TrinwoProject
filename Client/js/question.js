// Client/js/question.js
document.addEventListener('DOMContentLoaded', () => {
    const question = JSON.parse(localStorage.getItem('selectedQuestion'));
    const questionBox = document.getElementById('question-container');
    const answersBox = document.getElementById('answers-container');
    const form = document.getElementById('answer-form');
    const input = document.getElementById('answer-input');
  
    // Show the question
    questionBox.innerHTML = `
      <h2>${question.title}</h2>
      <p>${question.body}</p>
    `;
  
    // Get stored answers
    const key = `answers_${question.id}`;
    let answers = JSON.parse(localStorage.getItem(key)) || [];
  
    function renderAnswers() {
      answersBox.innerHTML = '';
      answers.forEach((ans, idx) => {
        answersBox.innerHTML += `<p><strong>${idx + 1}.</strong> ${ans}</p>`;
      });
    }
  
    renderAnswers();
  
    // Submit answer
    form.addEventListener('submit', (e) => {
      e.preventDefault();
      const newAnswer = input.value.trim();
      if (newAnswer) {
        answers.push(newAnswer);
        localStorage.setItem(key, JSON.stringify(answers));
        input.value = '';
        renderAnswers();
      }
    });
  
    // Add navigation button handlers
    document.getElementById('back-home').addEventListener('click', () => {
      window.location.href = 'home.html';
    });
    document.getElementById('edit-question').addEventListener('click', () => {
      alert('Edit Question feature coming soon!');
    });
  });
  