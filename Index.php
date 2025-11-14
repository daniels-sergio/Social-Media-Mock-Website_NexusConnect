<?php
session_start();


// Redirect to login if not authenticated
if (!isset($_SESSION['username']) || !isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit();
}


$chatUsername = $_GET['username'] ?? null;
$openChat = $_GET['action'] ?? null;
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NexusConnect - Connect Beyond Boundaries</title>
    <link rel="stylesheet" type="text/css" href="Styles/index_styles.css">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">NexusConnect</div>
        <div class="search-container">
            <input id="profileSearchBar" type="text" class="search-bar" placeholder="Search NexusConnect...">
             <div class="profile-search-results" id="profileSearchResults"></div>
        </div>
        <div class="user-menu">
            <div class="user-avatar" onclick="toggleSettings()">
                <img src="uploads/<?php echo isset($_SESSION['profile_picture']) ? htmlspecialchars($_SESSION['profile_picture']) : 'default.jpg'; ?>" alt="User Avatar">
            </div>
        </div>
    </nav>

    <!-- Messages Panel -->
    <div class="messages-panel">
        <div class="messages-header">
            <h2>Messages</h2>
            <button class="close-messages">&times;</button>
        </div>
        <div class="messages-tabs">
            <button class="tab active" data-tab="chats">Chats</button>
            <button class="tab" data-tab="new">New Message</button>
        </div>
        <div class="messages-content">
            <div class="tab-content active" id="chats">
                <div class="chat-list">
                    <!-- Existing chats will go here xxxxx -->
                </div>
            </div>
            <div class="tab-content" id="new">
                <div class="user-search">
                    <input type="text" placeholder="Search for users..." id="userSearchInput">
                    <div class="search-results"></div>
                </div>
            </div>
            <div class="tab-content" id="chat-window">
                <div class="chat-header">
                    <button class="back-button">&larr;</button>
                    <div class="chat-user-info">
                        <img src="" alt="" class="chat-user-avatar">
                        <div>
                            <div class="chat-user-name"></div>
                            <div class="chat-user-status">Online</div>
                        </div>
                    </div>
                </div>
                <div class="chat-messages"></div>
                <div class="chat-input-area">
                    <input type="text" placeholder="Type a message..." id="messageInput">
                    <button class="send-button">Send</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Panel -->
    <div id="settings-panel" class="settings-panel">
        <div class="settings-header">
            <h2>Settings</h2>
            <button class="close-settings">&times;</button>
        </div>
        <div class="settings-content">
            <form id="settingsForm" method="post" enctype="multipart/form-data" action="update_profile.php">
                <div class="profile-picture-section">
                    <img src="uploads/<?php echo isset($_SESSION['profile_picture']) ? htmlspecialchars($_SESSION['profile_picture']) : 'default.jpg'; ?>" alt="Profile Picture" id="profilePreview">
                    <input type="file" id="profilePicture" name="profile_picture" accept="image/*" hidden>
                    <button type="button" onclick="document.getElementById('profilePicture').click()" class="change-picture-btn">Change Picture</button>
                </div>
                
                <div class="settings-group">
                    <label>Full Name</label>
                    <input type="text" name="full_name" value="<?php echo htmlspecialchars($_SESSION['full_name']); ?>">
                </div>
                
                <div class="settings-group">
                    <label>Username</label>
                    <input type="text" name="username" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                </div>
                
                <div class="settings-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>">
                </div>
                
                <div class="settings-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" placeholder="Leave blank to keep current password">
                </div>
                
                <button type="submit" class="save-settings">Save Changes</button>
                
<button type="button" onclick="logout()" class="logout-btn" style="
    width: 100%;
    padding: 1rem;
    background: var(--accent);
    color: white;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    margin-top: 1rem;
    font-family: 'Inter', sans-serif;
    font-weight: 600;
    transition: all 0.3s ease;
">
    Logout
</button>
            </form>
        </div>
    </div>

    <!-- Main Container -->
    <div class="container">
        <!-- Left Sidebar -->
        <aside class="sidebar-left">
            <div class="profile-card">
                <div class="profile-pic-container">
                    <div class="progress-ring"></div>
                    <div class="profile-pic">
                        <img src="uploads/<?php echo isset($_SESSION['profile_picture']) ? htmlspecialchars($_SESSION['profile_picture']) : 'default.jpg'; ?>" alt="User Avatar">
                    </div>
                </div>
                <div class="profile-name">
                    <?php echo isset($_SESSION["full_name"]) ? htmlspecialchars($_SESSION["full_name"]) : "Guest"; ?>
                </div>
                <div class="profile-username">
                    <?php echo isset($_SESSION["username"]) ? "@" . htmlspecialchars($_SESSION["username"]) : ""; ?>
                </div>
               
            </div>

            <div class="nav-menu">
                
                <div class="nav-item" onclick="toggleMessages()">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path><path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path></svg>
                    Messages
                </div>
               

                <!-- I did not create a dedicated function to access the profile page,this was my own decision as the page can also be accessed from clicking on posts profile pictures -->
             <a href="profile.php?username=<?php echo $_SESSION['username']; ?>" class="nav-item" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                     <svg fill="currentColor" viewBox="0 0 20 20">
                     <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd">
                     </path>
                         </svg>
                               Profile
                </a>
                <div class="nav-item" onclick="toggleSettings()">
                    <svg fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path></svg>
                    Settings
                </div>
            </div>
        </aside>

        <!-- Main Feed -->
        <main class="main-feed">
            <!-- Create Post -->
            <div class="create-post">
                <div class="create-post-input">
                   <img src="uploads/<?php echo isset($_SESSION['profile_picture']) ? htmlspecialchars($_SESSION['profile_picture']) : 'default.jpg'; ?>" alt="User Avatar">
                    <input type="text" id="postContent" placeholder="<?php echo 'What\'s on your mind '. $_SESSION['username']?>">
                </div>
                <div id="imagePreviewContainer" style="display: none; margin: 1rem 0;">
                    <img id="imagePreview" style="max-width: 100%; max-height: 300px; border-radius: 10px;">
                    <button onclick="removeImage()" class="remove-image-btn">&times;</button>
                </div>
                <div class="create-post-actions">
                    <div class="post-action-btns">
                        <label for="imageInput" class="action-btn" title="Add Media">
                            <input type="file" id="imageInput" accept="image/*" style="display: none;" onchange="handleImageSelect(event)">
                            <svg fill="currentColor" viewBox="0 0 20 20" width="20" height="20"><path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path></svg>
                        </label>
                        
                    </div>
                    <button class="btn-post" onclick="createPost()">Post</button>
                </div>
            </div>
            <div id="posts-container">
                <!-- Posts will be loaded here -->
            </div>
        </main>

        <!-- Right Sidebar -->
        <aside class="sidebar-right">
            <div class="widget">
                <div class="widget-title">
                    <span>Trending Now</span>
                </div>
                <div class="trending-item">
                    <div class="trending-tag">#javascript</div>
                    <div class="trending-count">12.3K</div>
                </div>
                <div class="trending-item">
                    <div class="trending-tag">#php</div>
                    <div class="trending-count">8.9K</div>
                </div>
                <div class="trending-item active">
                    <div class="trending-tag">#Richfield</div>
                    <div class="trending-count">6.7K</div>
                </div>
                <div class="trending-item active">
                    <div class="trending-tag">#SRC2025</div>
                    <div class="trending-count">5.4K</div>
                </div>
            </div>

            <div class="widget">
                <div class="widget-title">
                    <span>Suggested Users</span>
                </div>
                <div id="suggestedUsers">
                    <!-- Users will be loaded here randomly -->
                </div>
            </div>
        </aside>
    </div>

    <script>

        function loadChats() {
    fetch('get_chats.php')
        .then(res => {
            if (!res.ok) {
                throw new Error('Network response was not ok');
            }
            return res.text(); // First get as text to check if it's valid JSON
        })
        .then(text => {
            try {
                const data = JSON.parse(text);
                
                // Check if we got an error response
                if (data.error) {
                    throw new Error(data.error);
                }
                
                const chatList = document.querySelector('.chat-list');
                
                if (data.length === 0) {
                    chatList.innerHTML = `
                        <div style="text-align: center; padding: 2rem; color: var(--text-secondary);">
                            <svg fill="currentColor" viewBox="0 0 20 20" width="60" height="60" style="opacity: 0.5; margin-bottom: 1rem;">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                            </svg>
                            <p>No conversations yet</p>
                            <p style="font-size: 0.9rem; margin-top: 0.5rem;">Start a new conversation to see it here!</p>
                        </div>
                    `;
                    return;
                }

                chatList.innerHTML = data.map(chat => `
                    <div class="chat-item" onclick="openChat('${chat.username}')">
                        <div class="chat-avatar">
                            <img src="uploads/${chat.profile_picture}" 
                                 alt="${chat.full_name}" 
                                 onerror="this.src='uploads/default.jpg'">
                        </div>
                        <div class="chat-info">
                            <div class="chat-header">
                                <div class="chat-user-name">${escapeHtml(chat.full_name)}</div>
                                <div class="chat-time">${formatChatTime(chat.last_message_time)}</div>
                            </div>
                            <div class="chat-preview">
                                <span class="last-message">${escapeHtml(chat.last_message.substring(0, 50))}${chat.last_message.length > 50 ? '...' : ''}</span>
                                ${chat.unread_count > 0 ? `
                                    <span class="unread-badge">${chat.unread_count}</span>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                `).join('');
            } catch (e) {
                console.error('Error parsing JSON:', e);
                console.error('Raw response:', text);
                throw new Error('Invalid JSON response: ' + text.substring(0, 100));
            }
        })
        .catch(error => {
            console.error('Error loading chats:', error);
            document.querySelector('.chat-list').innerHTML = `
                <div style="text-align: center; padding: 2rem; color: var(--accent);">
                    <p>Error loading conversations</p>
                    <p style="font-size: 0.8rem; margin-top: 0.5rem;">${error.message}</p>
                </div>
            `;
        });
}

function formatChatTime(timestamp) {
    const date = new Date(timestamp);
    const now = new Date();
    const diff = now - date; //Current time vs when message was sent
    
    if (diff < 10000) return 'Now';
    if (diff < 3600000) return `${Math.floor(diff/60000)}m`;
    if (diff < 86400000) return `${Math.floor(diff/3600000)}h`;
    if (diff < 604800000) return `${Math.floor(diff/86400000)}d`;
    return date.toLocaleDateString();
}

function openChat(username) {
    startChat(username);
}

let chatsRefreshInterval;

function startChatsRefresh() {
    // Refresh chats every 10 seconds when messages panel is open - which would update time
    chatsRefreshInterval = setInterval(() => {
        if (document.querySelector('.messages-panel.open')) {
            loadChats();
        }
    }, 10000);
}

function stopChatsRefresh() {
    clearInterval(chatsRefreshInterval);
}


        function toggleMessages() {
    const messagesPanel = document.querySelector('.messages-panel');
    const isOpening = !messagesPanel.classList.contains('open');
    
    messagesPanel.classList.toggle('open');
    
    if (isOpening) {
        loadChats();
        startChatsRefresh();
    } else {
        stopChatsRefresh();
    }
}
document.querySelector('.close-messages').addEventListener('click', () => {
    document.querySelector('.messages-panel').classList.remove('open');
    stopChatsRefresh();
});
        

        
        // logic for switching between tabs
    document.querySelectorAll('.messages-tabs .tab').forEach(tab => {
    tab.addEventListener('click', () => {
        const chatWindow = document.getElementById('chat-window');
        const chatsTab = document.getElementById('chats');
        const newTab = document.getElementById('new');
        
        document.querySelectorAll('.messages-tabs .tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        
        // Handle tab content display
        if (tab.dataset.tab === 'chats') {
            chatWindow.style.display = 'none';
            chatsTab.style.display = 'block';
            newTab.style.display = 'none';
            loadChats(); // Load chats when switching to chats tab
        } else if (tab.dataset.tab === 'new') {
            chatWindow.style.display = 'none';
            chatsTab.style.display = 'none';
            newTab.style.display = 'block';
        }
    });
});



        // Live search functionality
        let searchTimeout;
        const searchInput = document.getElementById('userSearchInput');
        const searchResults = document.querySelector('.search-results');

        
        
        
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            const query = searchInput.value;
            
            if (query.length < 2) {
                searchResults.innerHTML = '';
                return;
            }

            searchTimeout = setTimeout(() => {
                fetch(`search_users.php?q=${encodeURIComponent(query)}`) //Accesses search users file for logic and parses results as json
                    .then(res => res.json())
                    .then(users => {
                        searchResults.innerHTML = users.map(user => `
                            <div class="search-result" onclick="startChat('${user.username}')">
                                <img src="uploads/${user.profile_picture || 'default.jpg'}" alt="${user.username}">
                                <div>
                                    <div class="user-name">${user.full_name}</div>
                                    <div class="user-username">@${user.username}</div>
                                </div>
                            </div>
                        `).join('');
                    });
            }, 300);
        });

        function startChat(username) {
            const chatWindow = document.getElementById('chat-window');
            const chatsTab = document.getElementById('chats');
            const newTab = document.getElementById('new');
            
            // Update chat window header with profile picture
            fetch(`search_users.php?q=${username}`)
                .then(res => res.json())
                .then(users => {
                    const user = users[0]; // Get the first user since we're searching by exact username
                    document.querySelector('.chat-user-name').textContent = user.full_name;
                    document.querySelector('.chat-user-avatar').src = `uploads/${user.profile_picture || 'default.jpg'}`;
                    
                    // Show chat window, hide other tabs but keep their content
                    chatWindow.style.display = 'flex';
                    chatsTab.style.display = 'none';
                    newTab.style.display = 'none';
                    
                    // Update active tab
                    document.querySelectorAll('.messages-tabs .tab').forEach(tab => {
                        tab.classList.remove('active');
                    });
                    
                    // Load existing messages
                    loadChatHistory(username);
                });
            
            // Set up message input
            const messageInput = document.getElementById('messageInput');
            const sendButton = document.querySelector('.send-button');
            
            function sendMessage() {
                const message = messageInput.value.trim();
                if (message) {
                    const formData = new FormData();
                    formData.append('receiver', username);
                    formData.append('message', message);
                    
                    fetch('send_message.php', { //Fetches logic from send_message using method POST because its sensitive data
                        method: 'POST',
                        body: formData
                    })
                    .then(res => res.json()) 
                    .then(data => {
                        if (data.status === 'success') {
                            messageInput.value = '';
                            loadChatHistory(username);
                        }
                    });
                }
            }
            
            sendButton.onclick = sendMessage;
            messageInput.onkeypress = (e) => {
                if (e.key === 'Enter') {
                    sendMessage();
                }
            }
        }

        
        // Add back button handler
document.querySelector('.back-button').onclick = () => {
    const chatWindow = document.getElementById('chat-window');
    const chatsTab = document.getElementById('chats');
    
    chatWindow.style.display = 'none';
    chatsTab.style.display = 'block';
    
    // Reactivate the chats tab and reload chats
    document.querySelectorAll('.messages-tabs .tab').forEach(tab => {
        if (tab.dataset.tab === 'chats') {
            tab.classList.add('active');
        } else {
            tab.classList.remove('active');
        }
    });
    
    // Reload chats to show any new messages
    loadChats();
};

        function loadChatHistory(username) { //Same logic
            fetch(`get_chat.php?username=${encodeURIComponent(username)}`)
                .then(res => res.json())
                .then(messages => {
                    const chatMessages = document.querySelector('.chat-messages');
                    chatMessages.innerHTML = messages.map(msg => `
                        <div class="message ${msg.sender === '<?php echo $_SESSION["username"]; ?>' ? 'sent' : 'received'}">
                            <div class="content">${msg.message}</div>
                            <div class="time">${formatTime(msg.timestamp)}</div>
                        </div>
                    `).join('');
                    
                });
        }

        function formatTime(timestamp) { //Timestamp for message
            const date = new Date(timestamp);
            return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }

        

        // Opens setting panel
        function toggleSettings() {
            document.querySelector('.settings-panel').classList.toggle('open');
        }


        // Check if settings should be opened from URL parameter,in this case its when you are in the profile page and the edit profile button
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('openSettings') === 'true') {
        toggleSettings();
        
        // Clean the URL without reloading
        window.history.replaceState({}, document.title, 'Index.php');
    }
});

        document.querySelector('.close-settings').addEventListener('click', () => {
            document.querySelector('.settings-panel').classList.remove('open');
        });

        // Preview profile picture before upload
        document.getElementById('profilePicture').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profilePreview').src = e.target.result; //puts the photo in the target
                }
                reader.readAsDataURL(file);
            }
        });

        function logout() {
    if (confirm('Are you sure you want to logout?')) {
        window.location.href = 'logout.php'; //executes the logout logiv
    }
}

        // Function to load suggested users
        function loadSuggestedUsers() {
    fetch('get_suggested_users.php')
        .then(res => res.json())
        .then(users => { //randomly selects users from the database that is not the current users profile and allows the user to click on their profile
            document.getElementById('suggestedUsers').innerHTML = users.map(user => `
                <a href="profile.php?username=${user.username}" class="suggested-user-link">
                    <div class="suggested-user">
                        <img src="uploads/${user.profile_picture}" alt="${user.full_name}" 
                             onerror="this.src='uploads/default.jpg'">
                        <div class="suggested-user-info">
                            <div class="suggested-user-name">${user.full_name}</div>
                            <div class="suggested-user-username">@${user.username}</div>
                        </div>
                        <div class="view-profile-btn">
                            <svg fill="currentColor" viewBox="0 0 20 20" width="16" height="16">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                    </div>
                </a>
            `).join('');
        });
}

        // Load suggested users when page loads
        document.addEventListener('DOMContentLoaded', loadSuggestedUsers);

        let selectedImage = null;

        function handleImageSelect(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                    selectedImage = file;
                }
                reader.readAsDataURL(file);
            }
        }

        function removeImage() {
            document.getElementById('imagePreviewContainer').style.display = 'none';
            document.getElementById('imageInput').value = '';
            selectedImage = null;
        }

        function createPost() {
            const content = document.getElementById('postContent').value.trim();
            if (!content && !selectedImage) {
                return; // Exit if no content and no image
            }

            const formData = new FormData();
            if (content) {
                formData.append('content', content);
            }
            
            if (selectedImage) {
                formData.append('image', selectedImage);
            }

            // Show loading state
            const postButton = document.querySelector('.btn-post');
            const originalText = postButton.textContent;
            postButton.textContent = 'Posting...';
            postButton.disabled = true;

            fetch('create_post.php', {
                method: 'POST', //File name wont show in URL
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('postContent').value = '';
                    removeImage();
                    loadPosts();
                } else {
                    alert(data.message || 'Failed to create post');
                }
            })
            .catch(error => {
                alert('Error creating post: ' + error);
            })
            .finally(() => {
                // Reset button state
                postButton.textContent = originalText;
                postButton.disabled = false;
            });
        }

        function loadPosts() {
            fetch('get_posts.php')
                .then(res => res.json()) //Fetches result in JSON and embeds i
                .then(posts => {
                    const container = document.getElementById('posts-container');
                    container.innerHTML = posts.map(post => `
                        <article class="post ${post.post_type === 'image' ? 'type-image' : ''}">
                            <div class="post-header">
                                <a href="profile.php?username=${post.username}" class="post-author-link">
                                    <img src="uploads/${post.profile_picture || 'default.jpg'}" alt="Author" class="post-author-pic">
                                </a>
                                <div class="post-author-info">
                                    <a href="profile.php?username=${post.username}" class="post-author-link">
                                        <div class="post-author-name">${post.full_name}</div>
                                    </a>
                                    <div class="post-time">${formatTimestamp(post.created_at)}</div>
                                </div>
                            </div>
                            ${post.content ? `<div class="post-content">${post.content}</div>` : ''}
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
                                    <svg fill="currentColor" viewBox="0 0 20 20" width="20" height="20">
                                        <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                </svg>
                                    <span>Like</span>
                                </div>
                            </div>
                        </article>
                    `).join('');
                });
        }
        //To show when last messages were sent and keeps track via the database
        function formatTimestamp(timestamp) {
            const date = new Date(timestamp);
            const now = new Date();
            const diff = now - date;
            
            if (diff < 60000) return 'Just now';
            if (diff < 3600000) return `${Math.floor(diff/60000)}m ago`;
            if (diff < 86400000) return `${Math.floor(diff/3600000)}h ago`;
            return date.toLocaleDateString();
        }

        // Load posts when page loads
        document.addEventListener('DOMContentLoaded', loadPosts);

        document.addEventListener('DOMContentLoaded', () => {
            // Check for chat parameters in URL
            const urlParams = new URLSearchParams(window.location.search);
            const chatUsername = urlParams.get('username');
            const action = urlParams.get('action');
            
            if (action === 'chat' && chatUsername) {
                toggleMessages(); // Open messages panel
                setTimeout(() => {
                    startChat(chatUsername); // Start chat with user
                }, 300); // Small delay to ensure panel is open
                
                // Clean URL without reloading page
                window.history.replaceState({}, document.title, 'Index.php');
            }
        });


               // Profile Search Functionality
let profileSearchTimeout;
const profileSearchBar = document.getElementById('profileSearchBar');
const profileSearchResults = document.getElementById('profileSearchResults');

profileSearchBar.addEventListener('input', (e) => {
    clearTimeout(profileSearchTimeout);
    const query = e.target.value.trim();
    
    if (query.length < 2) { //only starts loading once there are more than 2 characters added for a search
        profileSearchResults.classList.remove('active');
        return;
    }

    profileSearchTimeout = setTimeout(() => {
        fetch(`search_profiles.php?q=${encodeURIComponent(query)}`)
            .then(res => res.json())
            .then(users => {
                if (users.length > 0) {
                    profileSearchResults.innerHTML = users.map(user => `
                        <a href="profile.php?username=${user.username}" class="profile-search-item">
                            <img src="uploads/${user.profile_picture}" 
                                 alt="${user.full_name}" 
                                 class="profile-search-avatar"
                                 onerror="this.src='uploads/default.jpg'">
                            <div class="profile-search-info">
                                <div class="profile-search-name">${escapeHtml(user.full_name)}</div>
                                <div class="profile-search-username">@${escapeHtml(user.username)}</div>
                            </div>
                        </a>
                    `).join('');
                } else {
                    profileSearchResults.innerHTML = `
                        <div class="profile-search-empty">
                            <svg fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                            <p>No users found matching "${escapeHtml(query)}"</p>
                        </div>
                    `;
                }
                profileSearchResults.classList.add('active');
            })
            .catch(error => { //will display if the function doesnt work properly
                console.error('Search error:', error);
                profileSearchResults.innerHTML = `
                    <div class="profile-search-empty">
                        <p>Error searching users</p> 
                    </div>
                `;
                profileSearchResults.classList.add('active');
            });
    }, 300);
});

// Close search results when clicking outside
document.addEventListener('click', (e) => {
    if (!e.target.closest('.search-container')) {
        profileSearchResults.classList.remove('active');
    }
});

// Prevent search results from closing when clicking inside
profileSearchResults.addEventListener('click', (e) => {
    e.stopPropagation();
});

// Clear search when clicking a result
profileSearchResults.addEventListener('click', (e) => {
    if (e.target.closest('.profile-search-item')) {
        profileSearchBar.value = '';
        profileSearchResults.classList.remove('active');
    }
});

// keyboard navigation for search results
profileSearchBar.addEventListener('keydown', (e) => {
    const items = profileSearchResults.querySelectorAll('.profile-search-item');
    const activeItem = profileSearchResults.querySelector('.profile-search-item:hover');
    
    if (e.key === 'Escape') {
        profileSearchResults.classList.remove('active');
        profileSearchBar.blur();
    } else if (e.key === 'Enter' && items.length > 0) {
        if (activeItem) {
            activeItem.click();
        } else {
            items[0].click();
        }
    }
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
    </script>
</body>
</html>