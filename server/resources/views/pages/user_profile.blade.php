<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Q&A Forum</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>

    </style>
</head>
<body>
    <!-- Sidebar Navigation -->
    <div class="sidebar">
        <div class="profile-header">
            <div class="profile-pic"></div>
            <h2 class="profile-name">Alex Morgan</h2>
            <p class="profile-title">Senior Developer & Tech Enthusiast</p>
            
            <div class="stats">
                <div class="stat-item">
                    <span class="stat-value">1.2K</span>
                    <span class="stat-label">Followers</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">850</span>
                    <span class="stat-label">Following</span>
                </div>
            </div>
        </div>
        
        <div class="nav-links">
            <div class="nav-item active" data-page="dashboard">
                <i class="fas fa-th-large"></i>
                <span>Dashboard</span>
            </div>
            <div class="nav-item" data-page="answers">
                <i class="fas fa-comment-dots"></i>
                <span>Answers</span>
            </div>
            <div class="nav-item" data-page="questions">
                <i class="fas fa-question-circle"></i>
                <span>Questions</span>
            </div>
            <div class="nav-item" data-page="posts">
                <i class="fas fa-file-alt"></i>
                <span>Posts</span>
            </div>
            <div class="nav-item" data-page="followers">
                <i class="fas fa-users"></i>
                <span>Followers</span>
            </div>
            <div class="nav-item" data-page="following">
                <i class="fas fa-user-friends"></i>
                <span>Following</span>
            </div>
            <div class="nav-item" data-page="bookmarks">
                <i class="fas fa-bookmark"></i>
                <span>Bookmarks</span>
            </div>
        </div>
    </div>
    
    <div class="menu-toggle">
        <i class="fas fa-bars"></i>
    </div>
    
    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <h1 class="page-title">
                <i class="fas fa-th-large"></i>
                <span>Dashboard Overview</span>
            </h1>
            <div class="search-bar">
                <input type="text" placeholder="Search content...">
                <button><i class="fas fa-search"></i></button>  
            </div>
        </div>
        <!-- Dashboard Page -->
        <div id="dashboard" class="page-content active">
            <div class="dashboard-stats">
                <div class="stat-card">
                    <i class="fas fa-question-circle"></i>
                    <div class="value">142</div>
                    <div class="label">Questions Asked</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-comments"></i>
                    <div class="value">327</div>
                    <div class="label">Answers Given</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-file-alt"></i>
                    <div class="value">42</div>
                    <div class="label">Posts Created</div>
                </div>
                <div class="stat-card">
                    <i class="fas fa-star"></i>
                    <div class="value">1,248</div>
                    <div class="label">Total Upvotes</div>
                </div>
            </div>
            
            <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Recent Activity</h2>
            
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">How to optimize React performance?</h3>
                    <div class="card-date">May 12, 2023</div>
                </div>
                <div class="card-content">
                    I've been working on a large React application and noticed some performance issues, especially with rendering large lists. What are the best practices for optimizing React performance in such scenarios?
                </div>
                <div class="card-stats">
                    <span><i class="fas fa-eye"></i> 2.4K views</span>
                    <span><i class="fas fa-comment"></i> 24 answers</span>
                    <span><i class="fas fa-heart"></i> 142 upvotes</span>
                </div>
            </div>
            
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Answered: Best practices for REST API security</h3>
                    <div class="card-date">May 10, 2023</div>
                </div>
                <div class="card-content">
                    When building RESTful APIs, security should be a top priority. Always implement HTTPS to encrypt data in transit. Use API keys for simple authentication between servers...
                </div>
                <div class="card-stats">
                    <span><i class="fas fa-eye"></i> 3.1K views</span>
                    <span><i class="fas fa-heart"></i> 87 upvotes</span>
                    <span><i class="fas fa-check-circle"></i> Accepted answer</span>
                </div>
            </div>
            
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">The future of web development in 2023</h3>
                    <div class="card-date">May 8, 2023</div>
                </div>
                <div class="card-content">
                    Web development continues to evolve at a rapid pace. This year, we're seeing significant advancements in serverless architectures, edge computing, and AI-powered development tools...
                </div>
                <div class="card-stats">
                    <span><i class="fas fa-eye"></i> 5.7K views</span>
                    <span><i class="fas fa-comment"></i> 32 comments</span>
                    <span><i class="fas fa-share"></i> 84 shares</span>
                </div>
            </div>
        </div>
        
        <!-- Answers Page -->
        <div id="answers" class="page-content">
            <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Answers</h2>
            
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Re: How to center a div?</h3>
                    <div class="card-date">May 15, 2023</div>
                </div>
                <div class="card-content">
                    There are several ways to center a div. The modern approach is to use flexbox. Simply apply "display: flex; justify-content: center; align-items: center;" to the parent container.
                </div>
                <div class="card-stats">
                    <span><i class="fas fa-heart"></i> 64 upvotes</span>
                    <span><i class="fas fa-check-circle"></i> Accepted answer</span>
                </div>
                <div class="card-actions">
                    <button class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn btn-outline"><i class="fas fa-trash"></i> Delete</button>
                </div>
            </div>
            
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">Re: Difference between let, const and var</h3>
                    <div class="card-date">May 12, 2023</div>
                </div>
                <div class="card-content">
                    The main differences are in scoping and reassignment. "var" is function-scoped, while "let" and "const" are block-scoped. "const" cannot be reassigned after declaration...
                </div>
                <div class="card-stats">
                    <span><i class="fas fa-heart"></i> 42 upvotes</span>
                </div>
                <div class="card-actions">
                    <button class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn btn-outline"><i class="fas fa-trash"></i> Delete</button>
                </div>
            </div>
        </div>
        
        <!-- Questions Page -->
        <div id="questions" class="page-content">
            <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Questions</h2>
            
            <div class="content-card">
                <div class="card-header">
                    <h3 class="card-title">How to implement JWT authentication securely?</h3>
                    <div class="card-date">May 14, 2023</div>
                </div>
                <div class="card-content">
                    I'm building a Node.js application and want to implement JWT authentication. What are the security best practices I should follow? How should I handle token expiration and refresh tokens?
                </div>
                <div class="card-stats">
                    <span><i class="fas fa-eye"></i> 1.2K views</span>
                    <span><i class="fas fa-comment"></i> 8 answers</span>
                </div>
                <div class="card-actions">
                    <button class="btn btn-primary"><i class="fas fa-edit"></i> Edit</button>
                    <button class="btn btn-outline"><i class="fas fa-trash"></i> Delete</button>
                </div>
            </div>
        </div>
        
        <!-- Followers Page -->
        <div id="followers" class="page-content">
            <h2 style="margin-bottom: 1.5rem; color: var(--primary-dark);">Your Followers</h2>
            
            <div class="user-grid">
                <div class="user-card">
                    <div class="user-header"></div>
                    <div class="user-body">
                        <div class="user-name">Sarah Johnson</div>
                        <div class="user-title">Frontend Developer</div>
                        <div class="user-stats">
                            <div class="user-stat">
                                <div class="user-stat-value">142</div>
                                <div class="user-stat-label">Posts</div>
                            </div>
                            <div class="user-stat">
                                <div class="user-stat-value">1.2K</div>
                                <div class="user-stat-label">Followers</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="user-card">
                    <div class="user-header"></div>
                    <div class="user-body">
                        <div class="user-name">Michael Chen</div>
                        <div class="user-title">UX Designer</div>
                        <div class="user-stats">
                            <div class="user-stat">
                                <div class="user-stat-value">87</div>
                                <div class="user-stat-label">Posts</div>
                            </div>
                            <div class="user-stat">
                                <div class="user-stat-value">845</div>
                                <div class="user-stat-label">Followers</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Other pages would follow similar structure -->
    </div>

    <script>
        // Navigation functionality
      
    </script>
</body>
</html>