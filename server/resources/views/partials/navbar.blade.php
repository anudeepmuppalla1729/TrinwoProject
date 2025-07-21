<nav class="navbar">
    <button class="hamburger" id="hamburgerBtn">
        <i class="bi bi-list"></i>
    </button>
    <div class="nav-left">
        <span class="logo">TRINWOPJ</span>
        <i class="bi bi-house-fill"></i>
        <button class="ask-btn">Ask Question</button>
    </div>
    <div class="search-box">
        <input type="text" placeholder="Search here..." />
        <i class="fa-solid fa-magnifying-glass"></i>
    </div>
    <div class="nav-links">
        <a href="#" class="contact"><i class="fa-regular fa-address-book"></i></a>
        <a href="#"><i class="fa-regular fa-bell"></i></a>
        <a href="/profile/dashboard"><i class="bi bi-person-circle"></i></a>
    </div>
    <style>
        @media (max-width: 768px) {
            .navbar .search-box {
                display: none !important;
            }

            .navbar .logo {
                display: none !important;
            }
        }
    </style>
</nav>