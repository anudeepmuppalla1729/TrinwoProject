<nav class="navbar">
    <div class="nav-left">
        <button class="hamburger" id="hamburgerBtn">
            <i class="bi bi-list"></i>
        </button>
        <span class="logo">TRINWOPJ</span>
        <i class="bi bi-house-fill"></i>
        <button class="ask-btn aks">Ask Question</button>
    </div>
    <div class="search-box">
        <input type="text" placeholder="Search here..." />
        <i class="fa-solid fa-magnifying-glass"></i>
    </div>
    <div class="nav-links">
        <a href="#" class="contact"><i class="fa-regular fa-address-book"></i></a>
        <a href="#"><i class="fa-regular fa-bell"></i></a>
        <a href="/profile/dashboard">
            @if(!empty(Auth::user()->avatar))
                <img src="{{ Storage::disk('s3')->url(Auth::user()->avatar) }}" alt="Profile" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
            @else
                <i class="bi bi-person-circle"></i>
            @endif
        </a>
    </div>
</nav> 