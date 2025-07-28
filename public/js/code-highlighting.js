// Code Highlighting and Copy Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Prism.js for syntax highlighting
    if (typeof Prism !== 'undefined') {
        Prism.highlightAll();
    }
    
    // Add copy functionality to code blocks
    addCopyButtonsToCodeBlocks();
    
    // Re-initialize after TinyMCE content changes
    if (typeof tinymce !== 'undefined') {
        tinymce.on('AddEditor', function(e) {
            e.editor.on('init', function() {
                setTimeout(function() {
                    addCopyButtonsToCodeBlocks();
                    if (typeof Prism !== 'undefined') {
                        Prism.highlightAll();
                    }
                }, 100);
            });
        });
    }
});

// Function to add copy buttons to code blocks
function addCopyButtonsToCodeBlocks() {
    const codeBlocks = document.querySelectorAll('pre code');
    
    codeBlocks.forEach(function(codeBlock) {
        const pre = codeBlock.parentElement;
        
        // Check if copy button already exists
        if (pre.querySelector('.copy-code-btn')) {
            return;
        }
        
        // Create copy button
        const copyBtn = document.createElement('button');
        copyBtn.className = 'copy-code-btn';
        copyBtn.innerHTML = '<i class="fas fa-copy"></i> Copy';
        copyBtn.onclick = function() {
            copyCode(this);
        };
        
        // Add button to pre element
        pre.style.position = 'relative';
        pre.appendChild(copyBtn);
    });
}

// Global copy function
function copyCode(button) {
    const codeBlock = button.parentElement.querySelector('code');
    const textToCopy = codeBlock.textContent || codeBlock.innerText;
    
    // Use modern clipboard API if available
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(textToCopy).then(function() {
            showCopySuccess(button);
        }).catch(function(err) {
            console.error('Failed to copy: ', err);
            fallbackCopyTextToClipboard(textToCopy, button);
        });
    } else {
        fallbackCopyTextToClipboard(textToCopy, button);
    }
}

// Fallback copy method for older browsers
function fallbackCopyTextToClipboard(text, button) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    textArea.style.top = '-999999px';
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showCopySuccess(button);
        } else {
            showCopyError(button);
        }
    } catch (err) {
        console.error('Fallback: Oops, unable to copy', err);
        showCopyError(button);
    }
    
    document.body.removeChild(textArea);
}

// Show copy success feedback
function showCopySuccess(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-check"></i> Copied!';
    button.style.background = '#28a745';
    
    setTimeout(function() {
        button.innerHTML = originalText;
        button.style.background = '#007bff';
    }, 2000);
}

// Show copy error feedback
function showCopyError(button) {
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-times"></i> Failed';
    button.style.background = '#dc3545';
    
    setTimeout(function() {
        button.innerHTML = originalText;
        button.style.background = '#007bff';
    }, 2000);
}

// Function to highlight code after content is loaded
function highlightCodeAfterLoad() {
    setTimeout(function() {
        if (typeof Prism !== 'undefined') {
            Prism.highlightAll();
        }
        addCopyButtonsToCodeBlocks();
    }, 500);
}

// Export functions for global use
window.copyCode = copyCode;
window.highlightCodeAfterLoad = highlightCodeAfterLoad;
window.addCopyButtonsToCodeBlocks = addCopyButtonsToCodeBlocks; 