// Admin JavaScript functionality
document.addEventListener('DOMContentLoaded', () => {
    // Image preview for file uploads
    const imageInputs = document.querySelectorAll('input[type="file"]');
    imageInputs.forEach(input => {
        input.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Remove existing preview
                    const existingPreview = input.parentNode.querySelector('.image-preview');
                    if (existingPreview) {
                        existingPreview.remove();
                    }
                    
                    // Create new preview
                    const preview = document.createElement('div');
                    preview.className = 'image-preview';
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="max-width: 200px; margin-top: 10px;">`;
                    input.parentNode.appendChild(preview);
                }
                reader.readAsDataURL(file);
            }
        });
    });
    
    // Rich text editor simulation
    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        if (textarea.id === 'content') {
            // This would be replaced with a proper rich text editor like TinyMCE or CKEditor
            textarea.style.minHeight = '300px';
            textarea.style.fontFamily = 'Arial, sans-serif';
            textarea.style.fontSize = '14px';
            textarea.style.lineHeight = '1.6';
        }
    });
    
    // Form validation
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.style.borderColor = 'red';
                } else {
                    field.style.borderColor = '';
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Ju lutem plotësoni të gjitha fushat e detyrueshme!');
            }
        });
    });
    
    // Auto-save draft functionality
    let autoSaveTimer;
    const contentField = document.getElementById('content');
    const titleField = document.getElementById('title');
    
    if (contentField && titleField) {
        [contentField, titleField].forEach(field => {
            field.addEventListener('input', () => {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(() => {
                    saveDraft();
                }, 2000);
            });
        });
    }
    
    function saveDraft() {
        const draft = {
            title: titleField?.value || '',
            content: contentField?.value || '',
            timestamp: new Date().toISOString()
        };
        
        localStorage.setItem('newsDraft', JSON.stringify(draft));
        
        // Show save indicator
        const indicator = document.createElement('div');
        indicator.textContent = 'Draft u ruajt: ' + new Date().toLocaleTimeString();
        indicator.style.cssText = `
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #4CAF50;
            color: white;
            padding: 10px;
            border-radius: 5px;
            z-index: 1000;
            font-size: 12px;
        `;
        
        document.body.appendChild(indicator);
        setTimeout(() => indicator.remove(), 2000);
    }
    
    // Load draft on page load
    const savedDraft = localStorage.getItem('newsDraft');
    if (savedDraft && titleField && contentField) {
        const draft = JSON.parse(savedDraft);
        if (confirm('Keni një draft të paruajtur. Dëshironi ta ngarkoni?')) {
            titleField.value = draft.title;
            contentField.value = draft.content;
        }
        
    }
    
});
