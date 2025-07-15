// Hamburger menu for mobile sidebar (shared for home and user_profile)
document.addEventListener('DOMContentLoaded', function() {
  const hamburgerBtn = document.getElementById('hamburgerBtn');
  const dashboardItems = document.querySelector('.dashboard_items');
  console.log('DOMContentLoaded: hamburgerBtn', hamburgerBtn, 'dashboardItems', dashboardItems);

  if (hamburgerBtn && dashboardItems) {
    hamburgerBtn.addEventListener('click', function(e) {
      e.stopPropagation();
      document.body.classList.toggle('sidebar-open');
      console.log('Hamburger clicked, sidebar toggled');
    });

    // Close sidebar when clicking outside (on overlay)
    document.addEventListener('click', function(e) {
      if (
        document.body.classList.contains('sidebar-open') &&
        !dashboardItems.contains(e.target) &&
        e.target !== hamburgerBtn &&
        !hamburgerBtn.contains(e.target)
      ) {
        document.body.classList.remove('sidebar-open');
        console.log('Sidebar closed by outside click');
      }
    });
  }
});

// Modal handling
const askButton = document.querySelector('.ask-btn'); // top navbar ask button
const sidebarAskButton = document.querySelector('.sidebar-ask-btn'); // sidebar ask button
const askModal = document.getElementById('askModal');
const closeButton = askModal.querySelector('.close-btn');
const cancelButton = askModal.querySelector('.cancel-btn');

// Show modal on top navbar and sidebar ask buttons
askButton.addEventListener('click', () => (askModal.style.display = 'flex'));
sidebarAskButton.addEventListener('click', (e) => {
  e.preventDefault();
  askModal.style.display = 'flex';
});

closeButton.addEventListener('click', () => (askModal.style.display = 'none'));
cancelButton.addEventListener('click', () => (askModal.style.display = 'none'));

// close on outside click
askModal.addEventListener('click', (e) => {
  if (e.target === askModal) askModal.style.display = 'none';
});

// Posts data
const posts = [
  {
    profileName: "Trinwo Solutions",
    profileLocation: "Software Development, Visakhapatnam, Andhra Pradesh",
    title: "What Future technology will take place??",
    body: `The future will bring smarter technology, from AI assistants to self-driving cars...`,
    answers: [
      "In the coming years, artificial intelligence will move far beyond its current limitations and truly become intuitive and adaptive. AI systems will learn continuously from real-world data, user interactions, and changing environments to fine-tune their behavior in real time. This will make them better at anticipating our needs, personalizing their responses, and handling complex situations without explicit instructions. By leveraging powerful learning algorithms and vast datasets, future AI will seamlessly integrate into daily life — making our tools and devices feel like personalized, intelligent assistants rather than machines following rigid scripts.",
    ],
  },
  {
    profileName: "TechWorld",
    profileLocation: "Mumbai, Maharashtra",
    title: "What is Quantum Computing's future?",
    body: `Quantum computing will unlock new capabilities in science and finance...`,
    answers: [
      "Quantum computing holds a future full of possibilities, with the potential to solve problems that are currently impossible for traditional computers. It could revolutionize fields like cryptography, drug discovery, climate modeling, and financial optimization by performing incredibly complex calculations at unmatched speeds. Researchers and companies are investing heavily to make quantum hardware more stable and practical for everyday use. As these advances continue, quantum computing will move from the lab to real-world industries — unlocking new insights, accelerating innovation, and reshaping technology as we know it.",
    ],
  },
  {
    profileName: "InnovTech",
    profileLocation: "Bengaluru, Karnataka",
    title: "How will Blockchain Technology impact the future of industries?",
    body: `Blockchain technology is more than just cryptocurrencies — it's a decentralized ledger with the power to transform industries like finance, supply chain, and healthcare...`,
    answers: [
      "Blockchain will fundamentally change how data and transactions are verified, secured, and shared across industries. Its decentralized and transparent nature eliminates the need for middlemen, reduces fraud, and enables real-time traceability. In the future, companies will use blockchain to improve security, streamline processes, and enhance trust between parties — unlocking new efficiencies and creating entirely new business models.",
    ],
  },
  {
    profileName: "NextGen Labs",
    profileLocation: "Pune, Maharashtra",
    title: "What is the future of 5G networks and connectivity?",
    body: `5G networks are rolling out rapidly across the world, promising superfast wireless speeds and ultra-low latency. But what will this mean for the future?`,
    answers: [
      "The future of 5G networks lies in unlocking real-time connectivity for billions of devices — enabling innovations like autonomous cars, smart cities, remote surgery, and seamless AR/VR experiences. Its ultra-low latency will make real-time communication feel instantaneous, and its high bandwidth will support data-heavy apps. Going forward, 5G will become a backbone for industrial automation and the Internet of Things, transforming everyday life and business in profound new ways.",
    ],
  },
  {
    profileName: "CosmicFrontiers",
    profileLocation: "Chennai, Tamil Nadu",
    title: "What are the future possibilities of Space Exploration?",
    body: `With advances in rocket technology and interest from companies like SpaceX and NASA, humanity's ambitions to explore space continue to grow...`,
    answers: [
      "Space exploration is entering an exciting new era, driven by reusable rockets, private-sector investments, and international collaborations. In the near future, we'll see lunar bases, Mars expeditions, and new orbital habitats that could support long-duration missions. Beyond this, innovations like in-orbit manufacturing, asteroid mining, and deep-space telescopes will help humanity explore further than ever before — expanding our scientific knowledge and pushing the boundaries of what's possible.",
    ],
  },
  {
    profileName: "EcoDrive",
    profileLocation: "Delhi, India",
    title: "How will Electric Vehicles shape our future transportation?",
    body: `Electric vehicles (EVs) have grown in popularity and adoption, but how will they change the way we travel and build cities in the long run?`,
    answers: [
      "Electric vehicles will be central to making transportation greener, quieter, and more efficient. With advances in battery capacity, charging infrastructure, and smart grids, EVs will soon offer longer ranges and faster recharging — making them as convenient as traditional cars. Paired with autonomous driving and shared mobility, EVs will help reduce pollution, decrease traffic congestion, and create more sustainable urban spaces where mobility is clean, safe, and accessible to everyone.",
    ],
  },
  {
    profileName: "MedTech Insights",
    profileLocation: "Hyderabad, Telangana",
    title: "What role will AI play in the future of Healthcare?",
    body: `Artificial intelligence is already making strides in diagnostics and treatment personalization, but what lies ahead?`,
    answers: [
      "AI will profoundly impact healthcare by making diagnostics faster, more accurate, and available even in remote areas. Machine learning will help doctors catch diseases earlier, identify personalized treatments, and improve patient outcomes. Beyond diagnostics, AI-driven tools will support drug discovery, robotic surgeries, and wearable health devices — creating a future where healthcare is more predictive, proactive, and tailored to each individual's unique needs.",
    ],
  },
];


// Render posts
const postsContainer = document.getElementById('postsContainer');

function renderPosts() {
  postsContainer.innerHTML = '';
  posts.forEach((post, index) => {
    postsContainer.innerHTML += `
      <div class="post" data-index="${index}" data-id="post-${index}">
        <div class="post-header">
          <div class="profile">
            <i class="bi bi-person-circle"></i>
            <div>
              <strong>${post.profileName}</strong><br>
              <small>${post.profileLocation}</small>
              <button style=" background: #c92ae0;
               border: 2px solid #a522b7;
               color: white;
               border-radius: 4px;
               cursor: pointer;
               margin-left: 5px;
               font-size: 0.9rem;">Follow</button>
            </div>
          </div>
          <div>
            <span class="options">⋮
              <div class="options-menu">
                <button>Answer</button><hr>
                <button>Not interested</button><hr>
                <button>Bookmark</button><hr>
                <button>Copy Link</button><hr>
                <button>Report</button>
              </div>
            </span>
            <span class="close-post">×</span>
          </div>
        </div>
        <hr>
        <h2>${post.title}</h2>
        <p>${post.body}</p>
        <div class="answers">
          ${post.answers.map((a) => `<p>${a}</p>`).join('')}
        </div>
        <div class="post-actions">
          <button>UpVote</button>
          <button>1.2k Seen</button>
          <button>DownVote</button>
          <button>Comment</button>
          <button>Share</button>
         
        </div>
      </div>
    `;
  });

  attachPostEvents();
}


renderPosts();

// Attach events for options menus and close
function attachPostEvents() {
  document.querySelectorAll('.options').forEach((opt) => {
    opt.addEventListener('click', (e) => {
      e.stopPropagation();
      closeAllMenus();
      opt.querySelector('.options-menu').classList.toggle('active');
    });
  });

  document.querySelectorAll('.close-post').forEach((btn) => {
    btn.addEventListener('click', () => btn.closest('.post').remove());
  });

  document.addEventListener('click', closeAllMenus);
}

function closeAllMenus() {
  document
    .querySelectorAll('.options-menu')
    .forEach((menu) => menu.classList.remove('active'));
}

const answerQuestionButton = document.querySelector('.dashboard_items .menu a:nth-child(3)'); // 3rd link is Answer Question

function renderAnswerQuestionsView() {
  const homeContent = document.querySelector('.home_content');
  homeContent.innerHTML = `
    <div class="answer-header" style="display:flex;align-items:center;justify-content:space-between;">
      <span style="font-size:1.5rem;font-weight:bold;">Answer</span>
      <span class="close-answer-view" style="font-size:2rem;cursor:pointer;">&times;</span>
    </div>
    <hr />
    <span style="background:#c92ae0;color:white;padding:0.3rem 1rem;border-radius:8px;font-weight:bold;font-size:1.1rem;">Questions</span>
    <div class="questions-list" style="margin-top:1rem;">
      ${posts.map((post, idx) => `
        <div class="question-card" style="background:#fff;border:1px solid #ccc;border-radius:10px;padding:1rem;margin-bottom:1rem;position:relative;">
          <div style="display:flex;align-items:center;justify-content:space-between;">
            <span style="font-size:1.1rem;font-weight:bold;">${post.title}</span>
            <span style="display:flex;align-items:center;gap:0.7rem;">
              <span class="question-options" style="font-size:1.5rem;cursor:pointer;">&#8942;
                <div class="options-menu">
                  <button >Answer</button<hr>
                  <button>Pass</button><hr>
                  <button>Bookmark</button>
                </div>
              </span>
              <span class="close-question" data-index="${idx}" style="font-size:1.5rem;cursor:pointer;">&times;</span>
            </span>
          </div>
          <div style="margin-top:0.5rem;">
            <button style="background:#c92ae0;color:white;border:none;border-radius:6px;padding:0.3rem 1rem;margin-right:0.5rem;">Answer 12</button>
            <button style="background:#c92ae0;color:white;border:none;border-radius:6px;padding:0.3rem 1rem;margin-right:0.5rem;">Pass</button>
            <button style="background:#c92ae0;color:white;border:none;border-radius:6px;padding:0.3rem 1rem;">Bookmark</button>
          </div>
        </div>
      `).join('')}
    </div>
  `;

  // Attach close event for the view (top right cross)
  const closeAnswerViewBtn = homeContent.querySelector('.close-answer-view');
  if (closeAnswerViewBtn) {
    closeAnswerViewBtn.addEventListener('click', function() {
      renderPosts();
    });
  }

  // Attach close event for each question
  homeContent.querySelectorAll('.close-question').forEach(btn => {
    btn.addEventListener('click', (e) => {
      const idx = btn.getAttribute('data-index');
      posts.splice(idx, 1);
      renderAnswerQuestionsView();
    });
  });

  // Attach options menu events
  homeContent.querySelectorAll('.question-options').forEach(opt => {
    opt.addEventListener('click', (e) => {
      e.stopPropagation();
      closeAllMenus();
      opt.querySelector('.options-menu').classList.toggle('active');
    });
  });
  document.addEventListener('click', closeAllMenus);
}

answerQuestionButton.addEventListener('click', function(e) {
  e.preventDefault();
  renderAnswerQuestionsView();
});


// Client/js/home.js
document.addEventListener('DOMContentLoaded', () => {
  const cards = document.querySelectorAll('.question-card');

  cards.forEach(card => {
    card.addEventListener('click', () => {
      const questionData = {
        id: card.dataset.id,
        title: card.dataset.title,
        body: card.dataset.body
      };

      // Save question to localStorage
      localStorage.setItem('selectedQuestion', JSON.stringify(questionData));

      // Go to question details page
      window.location.href = 'question.html';
    });
  });
});


const newsFeedButton = document.querySelector('.dashboard_items .menu a'); // First <a> in .menu is News Feed

newsFeedButton.addEventListener('click', function(e) {
  e.preventDefault();
  renderPosts();
});

// Handle click on hardcoded question cards (bottom of home.html)
document.querySelectorAll('.question-card').forEach(card => {
  card.addEventListener('click', () => {
    const id = card.getAttribute('data-id');
    const title = card.getAttribute('data-title');
    const body = card.getAttribute('data-body');
    const question = { id, title, body };
    localStorage.setItem('selectedQuestion', JSON.stringify(question));
    window.location.href = 'question.html';
  });
});

document.getElementById('qas-answer').addEventListener('click', () => {
  document.getElementById('answer-input').focus();
});
document.getElementById('qas-pass').addEventListener('click', () => {
  alert('You chose to pass on this question.');
});
document.getElementById('qas-bookmark').addEventListener('click', () => {
  alert('Question bookmarked! (Feature coming soon)');
});