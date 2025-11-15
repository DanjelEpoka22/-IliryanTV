// ✅ User Interactions JavaScript
class UserInteractions {
    constructor() {
        this.init();
    }

    init() {
        this.bindEvents();
        this.showAlerts();
    }

    bindEvents() {
        // Like Buttons
        document.querySelectorAll('.like-btn, .btn-like-sm').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleLike(e));
        });

        // Favorite Buttons
        document.querySelectorAll('.favorite-btn, .btn-favorite').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleFavorite(e));
        });

        // Remove Favorite Buttons
        document.querySelectorAll('.btn-remove-favorite').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleRemoveFavorite(e));
        });

        // Bookmark Buttons
        document.querySelectorAll('.btn-bookmark').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleBookmark(e));
        });

        // Remove Bookmark Buttons
        document.querySelectorAll('.btn-remove-bookmark').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleRemoveBookmark(e));
        });

        // Notification Buttons
        document.querySelectorAll('.btn-notification').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleNotification(e));
        });

        // Comment Forms
        document.querySelectorAll('#add-comment-form, .add-reply-form').forEach(form => {
            form.addEventListener('submit', (e) => this.handleCommentSubmit(e));
        });

        // Reply Buttons
        document.querySelectorAll('.reply-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.toggleReplyForm(e));
        });

        // Cancel Reply Buttons
        document.querySelectorAll('.cancel-reply').forEach(btn => {
            btn.addEventListener('click', (e) => this.cancelReply(e));
        });

        // Share Buttons
        document.querySelectorAll('.share-btn').forEach(btn => {
            btn.addEventListener('click', (e) => this.handleShare(e));
        });
    }

// ✅ LIKE FUNCTIONALITY - RREGULLO
async handleLike(e) {
    e.preventDefault();
    const button = e.currentTarget;
    
    // Nëse është link për login, lejo navigimin
    if (button.tagName === 'A') return;

    const newsId = button.dataset.newsId;
    if (!newsId) return;

    // Kontrollo nëse useri është i loguar
    if (!this.isUserLoggedIn()) {
        this.showAlert('Ju duhet të jeni të loguar për të pëlqyer lajme.', 'warning');
        return;
    }

    const isLiked = button.classList.contains('liked');
    
    try {
        button.classList.add('btn-loading');
        
        // RREGULLO: përdor 'sub_action' në vend të 'action'
        const response = await this.apiCall('like', {
            news_id: newsId,
            sub_action: isLiked ? 'remove' : 'add'  // NDRYSHIMI KRYESOR KËTU
        });

        if (response.success) {
            // Update button state
            button.classList.toggle('liked', !isLiked);
            
            // Update like text
            const likeText = button.querySelector('.like-text');
            if (likeText) {
                likeText.textContent = !isLiked ? 'Pëlqyer' : 'Pëlqej';
            }

            // Update like count
            this.updateLikeCount(newsId, response.likes_count, !isLiked);
            
            this.showAlert(
                !isLiked ? 'Lajmi u pëlqye me sukses!' : 'Pëlqimi u hoq!',
                'success'
            );
        } else {
            throw new Error(response.message || 'Gabim në pëlqim');
        }
    } catch (error) {
        console.error('Like error:', error);
        this.showAlert('Gabim në pëlqim: ' + error.message, 'error');
    } finally {
        button.classList.remove('btn-loading');
    }
}

// ✅ FAVORITE FUNCTIONALITY - RREGULLO
async handleFavorite(e) {
    e.preventDefault();
    const button = e.currentTarget;
    const newsId = button.dataset.newsId;
    
    if (!newsId) return;

    // Kontrollo nëse useri është i loguar
    if (!this.isUserLoggedIn()) {
        this.showAlert('Ju duhet të jeni të loguar për të shtuar në favorite.', 'warning');
        return;
    }

    const isFavorited = button.classList.contains('favorited');
    
    try {
        button.classList.add('btn-loading');
        
        // RREGULLO: përdor 'sub_action' në vend të 'action'
        const response = await this.apiCall('favorite', {
            news_id: newsId,
            sub_action: isFavorited ? 'remove' : 'add'  // NDRYSHIMI KRYESOR KËTU
        });

        if (response.success) {
            // Update button state
            button.classList.toggle('favorited', !isFavorited);
            
            // Update favorite text
            const favoriteText = button.querySelector('.favorite-text');
            if (favoriteText) {
                favoriteText.textContent = !isFavorited ? 'E Preferuar' : 'Shto në Favorite';
            }

            // Update title
            button.title = !isFavorited ? 'Hiq nga favorite' : 'Shto në favorite';

            // Update icon nëse është në overlay
            const icon = button.querySelector('i');
            if (icon) {
                icon.className = !isFavorited ? 'fas fa-star' : 'far fa-star';
            }
            
            this.showAlert(
                !isFavorited ? 'Lajmi u shtua në favorite!' : 'Lajmi u hoq nga favorite!',
                'success'
            );
        } else {
            throw new Error(response.message || 'Gabim në favorite');
        }
    } catch (error) {
        console.error('Favorite error:', error);
        this.showAlert('Gabim në favorite: ' + error.message, 'error');
    } finally {
        button.classList.remove('btn-loading');
    }
}

    // ✅ REMOVE FAVORITE (në faqen e favoriteve)
    async handleRemoveFavorite(e) {
        e.preventDefault();
        const button = e.currentTarget;
        const newsId = button.dataset.newsId;
        const card = button.closest('.favorite-card');
        
        if (!newsId || !card) return;

        try {
            button.classList.add('btn-loading');
            
            const response = await this.apiCall('favorite', {
                news_id: newsId,
                action: 'remove'
            });

            if (response.success) {
                // Animoj heqjen e kartelës
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'translateX(-100%)';
                
                setTimeout(() => {
                    card.remove();
                    this.checkEmptyState('favorites');
                }, 300);
                
                this.showAlert('Lajmi u hoq nga favorite!', 'success');
            } else {
                throw new Error(response.message || 'Gabim në heqje nga favorite');
            }
        } catch (error) {
            console.error('Remove favorite error:', error);
            this.showAlert('Gabim në heqje nga favorite: ' + error.message, 'error');
        } finally {
            button.classList.remove('btn-loading');
        }
    }

    // ✅ BOOKMARK FUNCTIONALITY
    async handleBookmark(e) {
        e.preventDefault();
        const button = e.currentTarget;
        
        // Nëse është link për login, lejo navigimin
        if (button.tagName === 'A') return;

        const showId = button.dataset.showId;
        
        if (!showId) return;

        // Kontrollo nëse useri është i loguar
        if (!this.isUserLoggedIn()) {
            this.showAlert('Ju duhet të jeni të loguar për të shtuar në bookmark.', 'warning');
            return;
        }

        const isBookmarked = button.classList.contains('bookmarked');
        
        try {
            button.classList.add('btn-loading');
            
            const response = await this.apiCall('bookmark', {
                show_id: showId,
                action: isBookmarked ? 'remove' : 'add'
            });

            if (response.success) {
                // Update button state
                button.classList.toggle('bookmarked', !isBookmarked);

                // Update title
                button.title = !isBookmarked ? 'Hiq bookmark' : 'Shto në bookmark';

                // Update icon
                const icon = button.querySelector('i');
                if (icon) {
                    icon.className = !isBookmarked ? 'fas fa-bookmark' : 'far fa-bookmark';
                }
                
                this.showAlert(
                    !isBookmarked ? 'Programi u shtua në bookmark!' : 'Programi u hoq nga bookmark!',
                    'success'
                );
            } else {
                throw new Error(response.message || 'Gabim në bookmark');
            }
        } catch (error) {
            console.error('Bookmark error:', error);
            this.showAlert('Gabim në bookmark: ' + error.message, 'error');
        } finally {
            button.classList.remove('btn-loading');
        }
    }

    // ✅ REMOVE BOOKMARK (në faqen e bookmarkeve)
    async handleRemoveBookmark(e) {
        e.preventDefault();
        const button = e.currentTarget;
        const showId = button.dataset.showId;
        const card = button.closest('.bookmark-card');
        
        if (!showId || !card) return;

        try {
            button.classList.add('btn-loading');
            
            const response = await this.apiCall('bookmark', {
                show_id: showId,
                action: 'remove'
            });

            if (response.success) {
                // Animoj heqjen e kartelës
                card.style.transition = 'all 0.3s ease';
                card.style.opacity = '0';
                card.style.transform = 'translateX(-100%)';
                
                setTimeout(() => {
                    card.remove();
                    this.checkEmptyState('bookmarks');
                }, 300);
                
                this.showAlert('Programi u hoq nga bookmark!', 'success');
            } else {
                throw new Error(response.message || 'Gabim në heqje nga bookmark');
            }
        } catch (error) {
            console.error('Remove bookmark error:', error);
            this.showAlert('Gabim në heqje nga bookmark: ' + error.message, 'error');
        } finally {
            button.classList.remove('btn-loading');
        }
    }

    // ✅ NOTIFICATION FUNCTIONALITY
    async handleNotification(e) {
        e.preventDefault();
        const button = e.currentTarget;
        
        // Nëse është link për login, lejo navigimin
        if (button.tagName === 'A') return;

        const eventId = button.dataset.eventId;
        
        if (!eventId) return;

        // Kontrollo nëse useri është i loguar
        if (!this.isUserLoggedIn()) {
            this.showAlert('Ju duhet të jeni të loguar për të kërkuar notifikime.', 'warning');
            return;
        }

        const isNotifying = button.classList.contains('notifying');
        
        try {
            button.classList.add('btn-loading');
            
            const response = await this.apiCall('notification', {
                event_id: eventId,
                action: isNotifying ? 'remove' : 'add'
            });

            if (response.success) {
                // Update button state
                button.classList.toggle('notifying', !isNotifying);

                // Update title
                button.title = !isNotifying ? 'Hiq notifikim' : 'Kërko notifikim';

                // Update icon
                const icon = button.querySelector('i');
                if (icon) {
                    icon.className = !isNotifying ? 'fas fa-bell' : 'far fa-bell';
                }
                
                this.showAlert(
                    !isNotifying ? 'Notifikimi u aktivizua!' : 'Notifikimi u çaktivizua!',
                    'success'
                );
            } else {
                throw new Error(response.message || 'Gabim në notifikim');
            }
        } catch (error) {
            console.error('Notification error:', error);
            this.showAlert('Gabim në notifikim: ' + error.message, 'error');
        } finally {
            button.classList.remove('btn-loading');
        }
    }

    // ✅ COMMENT FUNCTIONALITY
    async handleCommentSubmit(e) {
        e.preventDefault();
        const form = e.currentTarget;
        const formData = new FormData(form);
        const commentText = formData.get('comment_text') || formData.get('reply_text');
        
        if (!commentText.trim()) {
            this.showAlert('Ju lutem shkruani një koment.', 'warning');
            return;
        }

        // Kontrollo nëse useri është i loguar
        if (!this.isUserLoggedIn()) {
            this.showAlert('Ju duhet të jeni të loguar për të komentuar.', 'warning');
            return;
        }

        const newsId = form.dataset.newsId;
        const parentId = form.dataset.parentId;
        
        try {
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Duke postuar...';
            submitBtn.disabled = true;

            const response = await this.apiCall('comment', {
                news_id: newsId,
                comment_text: commentText.trim(),
                parent_id: parentId || null
            });

            if (response.success) {
                form.reset();
                
                // Fshi formën e reply nëse ekziston
                if (parentId) {
                    const replyForm = form.closest('.reply-form');
                    if (replyForm) {
                        replyForm.style.display = 'none';
                    }
                }

                // Shto komentin në listë
                this.addCommentToDOM(response.comment, parentId);
                
                this.showAlert('Komenti u postua me sukses!', 'success');
            } else {
                throw new Error(response.message || 'Gabim në postimin e komentit');
            }
        } catch (error) {
            console.error('Comment error:', error);
            this.showAlert('Gabim në postimin e komentit: ' + error.message, 'error');
        } finally {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }
    }

    // ✅ SHARE FUNCTIONALITY
    handleShare(e) {
        e.preventDefault();
        const button = e.currentTarget;
        const newsId = button.dataset.newsId;
        const url = `${window.location.origin}${window.location.pathname}?id=${newsId}`;
        
        if (navigator.share) {
            // Web Share API
            navigator.share({
                title: document.title,
                url: url
            }).catch(err => {
                console.log('Share cancelled:', err);
            });
        } else if (navigator.clipboard) {
            // Copy to clipboard
            navigator.clipboard.writeText(url).then(() => {
                this.showAlert('Linku u kopjua në clipboard!', 'success');
            }).catch(err => {
                // Fallback to prompt
                this.showSharePrompt(url);
            });
        } else {
            // Fallback to prompt
            this.showSharePrompt(url);
        }
    }

    // ✅ REPLY FORM TOGGLE
    toggleReplyForm(e) {
        e.preventDefault();
        const button = e.currentTarget;
        const commentId = button.dataset.commentId;
        const replyForm = button.closest('.comment-item').querySelector('.reply-form');
        
        if (replyForm) {
            const isVisible = replyForm.style.display === 'block';
            replyForm.style.display = isVisible ? 'none' : 'block';
        }
    }

    cancelReply(e) {
        e.preventDefault();
        const button = e.currentTarget;
        const replyForm = button.closest('.reply-form');
        
        if (replyForm) {
            replyForm.style.display = 'none';
            const form = replyForm.querySelector('form');
            if (form) form.reset();
        }
    }

    // ✅ HELPER METHODS
    async apiCall(action, data) {
        const formData = new FormData();
        formData.append('action', action);
        
        Object.keys(data).forEach(key => {
            if (data[key] !== null && data[key] !== undefined) {
                formData.append(key, data[key]);
            }
        });

        const response = await fetch('user_actions.php', {
            method: 'POST',
            body: formData
        });

        return await response.json();
    }

    isUserLoggedIn() {
        return document.querySelector('.navbar .nav-link.text-success') !== null;
    }

    updateLikeCount(newsId, count, added) {
        // Update në butonin e madh
        const likeBtn = document.querySelector(`.like-btn[data-news-id="${newsId}"]`);
        if (likeBtn) {
            const countBadge = likeBtn.querySelector('.likes-count-badge');
            if (countBadge) {
                countBadge.textContent = count;
            }
        }

        // Update në butonin e vogël
        const likeBtnSm = document.querySelector(`.btn-like-sm[data-news-id="${newsId}"]`);
        if (likeBtnSm) {
            // Nëse ka badge, update; përndryshe mund të shtojmë një
        }

        // Update në meta
        const likesCountEl = document.querySelector(`.likes-count`);
        if (likesCountEl) {
            likesCountEl.innerHTML = `<i class="fas fa-heart me-1"></i>${count} Like`;
        }
    }

    addCommentToDOM(comment, parentId = null) {
        const commentsList = parentId 
            ? document.querySelector(`.comment-item[data-comment-id="${parentId}"] .replies`)
            : document.getElementById('comments-list');

        if (!commentsList) return;

        const commentHTML = this.createCommentHTML(comment, parentId);
        
        if (parentId) {
            // Shto si reply
            if (commentsList.querySelector('.no-replies')) {
                commentsList.innerHTML = '';
            }
            commentsList.insertAdjacentHTML('beforeend', commentHTML);
        } else {
            // Shto si koment të ri në fillim
            commentsList.insertAdjacentHTML('afterbegin', commentHTML);
            
            // Fshi mesazhin "asnjë koment" nëse ekziston
            const noComments = commentsList.querySelector('.text-center');
            if (noComments) {
                noComments.remove();
            }
        }

        // Bind events për komentin e ri
        this.bindCommentEvents(commentsList.lastElementChild);
    }

    createCommentHTML(comment, parentId = null) {
        const isReply = parentId !== null;
        const avatarHTML = comment.profile_image 
            ? `<img src="${comment.profile_image}" alt="${comment.username}" class="rounded-circle" width="${isReply ? '30' : '40'}" height="${isReply ? '30' : '40'}">`
            : `<div class="avatar-placeholder rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: ${isReply ? '30' : '40'}px; height: ${isReply ? '30' : '40'}px;">
                  <i class="fas fa-user"></i>
               </div>`;

        if (isReply) {
            return `
                <div class="reply-item mb-2 pb-2 border-start border-3 ps-3">
                    <div class="d-flex">
                        <div class="reply-avatar me-2">
                            ${avatarHTML}
                        </div>
                        <div class="reply-content">
                            <div class="reply-header d-flex justify-content-between align-items-center mb-1">
                                <strong class="reply-author small">${comment.username}</strong>
                                <small class="reply-date text-muted">${comment.created_at}</small>
                            </div>
                            <div class="reply-text small">
                                ${this.escapeHTML(comment.comment_text).replace(/\n/g, '<br>')}
                            </div>
                        </div>
                    </div>
                </div>
            `;
        } else {
            return `
                <div class="comment-item mb-3" data-comment-id="${comment.id}">
                    <div class="d-flex">
                        <div class="comment-avatar me-3">
                            ${avatarHTML}
                        </div>
                        <div class="comment-content flex-grow-1">
                            <div class="comment-header d-flex justify-content-between align-items-center mb-2">
                                <strong class="comment-author">${comment.username}</strong>
                                <small class="comment-date text-muted">${comment.created_at}</small>
                            </div>
                            <div class="comment-text">
                                ${this.escapeHTML(comment.comment_text).replace(/\n/g, '<br>')}
                            </div>
                            <div class="comment-actions mt-2">
                                <button class="btn btn-sm btn-outline-secondary reply-btn" 
                                        data-comment-id="${comment.id}">
                                    <i class="fas fa-reply me-1"></i>Përgjigju
                                </button>
                            </div>
                            
                            <div class="reply-form mt-3" style="display: none;">
                                <form class="add-reply-form" data-parent-id="${comment.id}">
                                    <div class="mb-2">
                                        <textarea class="form-control form-control-sm" name="reply_text" placeholder="Shkruaj përgjigjen..." rows="2" required></textarea>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-paper-plane me-1"></i>Posto Përgjigje
                                        </button>
                                        <button type="button" class="btn btn-secondary btn-sm cancel-reply">
                                            <i class="fas fa-times me-1"></i>Anulo
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="replies mt-3 ms-4">
                                <!-- Replies will be added here -->
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }
    }

    bindCommentEvents(commentElement) {
        // Reply button
        const replyBtn = commentElement.querySelector('.reply-btn');
        if (replyBtn) {
            replyBtn.addEventListener('click', (e) => this.toggleReplyForm(e));
        }

        // Reply form
        const replyForm = commentElement.querySelector('.add-reply-form');
        if (replyForm) {
            replyForm.addEventListener('submit', (e) => this.handleCommentSubmit(e));
        }

        // Cancel reply button
        const cancelReplyBtn = commentElement.querySelector('.cancel-reply');
        if (cancelReplyBtn) {
            cancelReplyBtn.addEventListener('click', (e) => this.cancelReply(e));
        }
    }

    checkEmptyState(pageType) {
        const grid = document.querySelector(`.${pageType}-grid`);
        if (grid && grid.children.length === 0) {
            // Krijo empty state nëse nuk ekziston
            this.createEmptyState(pageType);
        }
    }

    createEmptyState(pageType) {
        const container = document.querySelector('.container');
        const pageTitles = {
            'favorites': 'Lajmet e Preferuara',
            'bookmarks': 'Programet e Bookmarked'
        };
        
        const emptyStateHTML = `
            <div class="empty-state text-center py-5">
                <div class="empty-icon mb-4">
                    <i class="fas fa-${pageType === 'favorites' ? 'heart' : 'bookmark'} fa-4x text-muted"></i>
                </div>
                <h3 class="empty-title">Asnjë ${pageType === 'favorites' ? 'lajm i preferuar' : 'program i bookmarked'}</h3>
                <p class="empty-text text-muted mb-4">
                    Ju nuk keni asnjë ${pageType === 'favorites' ? 'lajm të shënuar si të preferuar' : 'program të shënuar si të bookmarked'}. 
                    Shkoni te faqja e ${pageType === 'favorites' ? 'lajmeve' : 'programeve TV'} për të shënuar disa ${pageType === 'favorites' ? 'lajme' : 'programe'}.
                </p>
                <a href="${pageType === 'favorites' ? 'news.php' : 'shows.php'}" class="btn btn-primary">
                    <i class="fas fa-${pageType === 'favorites' ? 'newspaper' : 'tv'} me-2"></i>Shiko ${pageType === 'favorites' ? 'Lajmet' : 'Programet TV'}
                </a>
            </div>
        `;

        container.querySelector(`.${pageType}-grid`).innerHTML = emptyStateHTML;
    }

    showSharePrompt(url) {
        const shareInput = document.createElement('input');
        shareInput.value = url;
        document.body.appendChild(shareInput);
        shareInput.select();
        shareInput.setSelectionRange(0, 99999);
        document.execCommand('copy');
        document.body.removeChild(shareInput);
        this.showAlert('Linku u kopjua në clipboard!', 'success');
    }

    showAlert(message, type = 'info') {
        // Krijo alert element
        const alertDiv = document.createElement('div');
        alertDiv.className = `user-alert alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            <i class="fas fa-${this.getAlertIcon(type)} me-2"></i>${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        // Shto në DOM
        document.body.appendChild(alertDiv);

        // Fshi automatikisht pas 5 sekondash
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    getAlertIcon(type) {
        const icons = {
            'success': 'check-circle',
            'error': 'exclamation-circle',
            'warning': 'exclamation-triangle',
            'info': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    escapeHTML(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    showAlerts() {
        // Shfaq alert-et e URL-ës (p.sh. ?success=1)
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            this.showAlert('Operacioni u krye me sukses!', 'success');
        }
        if (urlParams.has('error')) {
            this.showAlert('Ndodhi një gabim. Ju lutem provoni përsëri.', 'error');
        }
    }
}

// ✅ Initialize kur DOM të jetë gati
document.addEventListener('DOMContentLoaded', function() {
    new UserInteractions();
});

// ✅ Global functions për accessibility
window.userInteractions = {
    like: function(newsId) {
        const btn = document.querySelector(`[data-news-id="${newsId}"]`);
        if (btn) btn.click();
    },
    
    favorite: function(newsId) {
        const btn = document.querySelector(`.btn-favorite[data-news-id="${newsId}"]`);
        if (btn) btn.click();
    },
    
    bookmark: function(showId) {
        const btn = document.querySelector(`.btn-bookmark[data-show-id="${showId}"]`);
        if (btn) btn.click();
    }
};