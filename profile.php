<?php
session_start();
require_once("database.php");


// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Get username from URL or use current user's username
$profile_username = $_GET['username'] ?? $_SESSION['username'];
$is_own_profile = ($profile_username === $_SESSION['username']);

// Fetch profile user's information
$sql = "SELECT username, full_name, email, profile_picture, register_date 
        FROM users 
        WHERE username = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "s", $profile_username);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$profile_user = mysqli_fetch_assoc($result);

if (!$profile_user) {
    header("Location: index.php");
    exit;
}

$profile_user_id = $profile_user['id'];

// Get post count
$post_count_sql = "SELECT COUNT(*) as post_count FROM posts WHERE username = ?";
$post_count_stmt = mysqli_prepare($conn, $post_count_sql);
mysqli_stmt_bind_param($post_count_stmt, "i", $profile_user_id);
mysqli_stmt_execute($post_count_stmt);
$post_count_result = mysqli_stmt_get_result($post_count_stmt);
$post_count_row = mysqli_fetch_assoc($post_count_result);
$post_count = $post_count_row['post_count'];


mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($profile_user['full_name']); ?> - NexusConnect</title>
     <link rel="stylesheet" type="text/css" href="Styles/profile_styles.css">
       
 
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <a href="index.php" class="logo">NexusConnect</a>
        <div class="nav-actions">
            <a href="index.php" class="btn-back">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                </svg>
                Back to Feed
            </a>
        </div>
    </nav>

    <!-- Profile Header -->
    <div class="profile-header">
        <div class="cover-photo"></div>
        <div class="cover-overlay"></div>
    </div>

    <!-- Profile Container -->
    <div class="profile-container">
        <!-- Profile Top Section -->
        <div class="profile-top">
            <img src="uploads/<?php echo htmlspecialchars($profile_user['profile_picture'] ?? 'default.jpg'); ?>" 
                 alt="<?php echo htmlspecialchars($profile_user['full_name']); ?>" 
                 class="profile-pic-large">
            
            <div class="profile-info">
                <div class="profile-name-section">
                    <h1 class="profile-name"><?php echo htmlspecialchars($profile_user['full_name']); ?></h1>
                </div>
                <div class="profile-username">@<?php echo htmlspecialchars($profile_user['username']); ?></div>
                
                <div class="profile-actions">
                    <?php if ($is_own_profile): ?>
                        <button class="btn-primary" onclick="toggleSettings()">
                            Edit Profile
                        </button>
                    <?php else: ?>
                        <button class="btn-primary" onclick="sendMessage('<?php echo htmlspecialchars($profile_user['username']); ?>')">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20" style="display: inline; vertical-align: middle;">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                            </svg>
                            Message
                        </button>
                        
                    <?php endif; ?>
                </div>
            </div>
        </div>

     

        <!-- Posts Section -->
        <div class="posts-section">
            <div class="section-header">
                <h2 class="section-title">
                    <?php echo $is_own_profile ? 'Your Posts' : htmlspecialchars($profile_user['username']) . "'s Posts"; ?>
                </h2>
            </div>
            
            <div id="posts-container">
                <!-- Posts will be loaded here -->
            </div>
        </div>
    </div>

    <script>
    const profileUsername = '<?php echo addslashes($profile_username); ?>';
    const isOwnProfile = <?php echo $is_own_profile ? 'true' : 'false'; ?>;

    // Load user's posts - FIXED: passing username instead of user_id
    function loadUserPosts() {
        fetch(`get_user_posts.php?username=${encodeURIComponent(profileUsername)}`)
            .then(res => res.json())
            .then(posts => {
                const container = document.getElementById('posts-container');
                
                if (posts.error) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <p>Error: ${posts.error}</p>
                        </div>
                    `;
                    return;
                }
                
                if (!posts || posts.length === 0) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 5a2 2 0 012-2h10a2 2 0 012 2v8a2 2 0 01-2 2h-2.22l.123.489.804.804A1 1 0 0113 18H7a1 1 0 01-.707-1.707l.804-.804L7.22 15H5a2 2 0 01-2-2V5zm5.771 7H5V5h10v7H8.771z" clip-rule="evenodd"></path>
                            </svg>
                            <h3>${isOwnProfile ? 'No posts yet' : 'No posts to show'}</h3>
                            <p>${isOwnProfile ? 'Share your first post to get started!' : 'This user hasn\'t posted anything yet.'}</p>
                        </div>
                    `;
                    return;
                }

                container.innerHTML = posts.map(post => `
                    <article class="post ${post.post_type === 'image' ? 'type-image' : ''}">
                        <div class="post-header">
                            <img src="uploads/${post.profile_picture || 'default.jpg'}" 
                                 alt="Author" 
                                 class="post-author-pic"
                                 onerror="this.src='uploads/default.jpg'">
                            <div class="post-author-info">
                                <div class="post-author-name">${escapeHtml(post.full_name)}</div>
                                <div class="post-time">${formatTimestamp(post.created_at)}</div>
                            </div>
                        </div>
                        ${post.content ? `<div class="post-content">${escapeHtml(post.content)}</div>` : ''}
                        ${post.image_path ? `
                            <div class="post-image-container">
                                <img src="uploads/posts/${post.image_path}" 
                                    alt="Post image" 
                                    class="post-image"
                                    onerror="this.style.display='none'"
                                >
                            </div>
                        ` : ''}
                        <div class="post-actions">
                            <div class="post-action">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Like</span>
                            </div>
                            <div class="post-action">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7z" clip-rule="evenodd"></path>
                                </svg>
                                <span>Comment</span>
                            </div>
                            <div class="post-action">
                                <svg fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M15 8a3 3 0 10-2.977-2.63l-4.94 2.47a3 3 0 100 4.319l4.94 2.47a3 3 0 10.895-1.789l-4.94-2.47a3.027 3.027 0 000-.74l4.94-2.47C13.456 7.68 14.19 8 15 8z"></path>
                                </svg>
                                <span>Share</span>
                            </div>
                        </div>
                    </article>
                `).join('');
            })
            .catch(error => {
                console.error('Error loading posts:', error);
                document.getElementById('posts-container').innerHTML = `
                    <div class="empty-state">
                        <h3>Error loading posts</h3>
                        <p>Please try again later.</p>
                    </div>
                `;
            });
    }

    
    // Format timestamp
    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        const now = new Date();
        const diff = now - date;
        
        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return `${Math.floor(diff/60000)}m ago`;
        if (diff < 86400000) return `${Math.floor(diff/3600000)}h ago`;
        if (diff < 604800000) return `${Math.floor(diff/86400000)}d ago`;
        return date.toLocaleDateString();
    }

    // Escape HTML
    function escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Send message to user
    function sendMessage(username) {
        window.location.href = `index.php?action=chat&username=${encodeURIComponent(username)}`;
    }

    // Load posts on page load
    document.addEventListener('DOMContentLoaded', () => {
        console.log('Loading posts for:', profileUsername);
        loadUserPosts();
    });

    function toggleSettings() {
    // Redirect to index.php with a parameter to open settings
    window.location.href = 'index.php?openSettings=true';
}
</script>
</body>
</html>