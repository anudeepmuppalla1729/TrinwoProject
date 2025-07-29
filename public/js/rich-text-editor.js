// Rich Text Editor Configuration
document.addEventListener('DOMContentLoaded', function () {
    // Check if TinyMCE is available
    if (typeof tinymce === 'undefined') {
        console.error('TinyMCE is not loaded. Please check if the script is included correctly.');
        return;
    }

    // Debug function to check API key status
    function checkApiKeyStatus() {
        if (window.tinymceApiKey && window.tinymceApiKey.trim() !== '') {
            console.log('✅ TinyMCE API key found:', window.tinymceApiKey.substring(0, 10) + '...');
            return true;
        } else {
            console.log('❌ No TinyMCE API key found. Add TINYMCE_API_KEY to your .env file');
            return false;
        }
    }

    // Function to ensure API key is properly applied
    function ensureApiKeyApplied() {
        if (!window.tinymceApiKey || window.tinymceApiKey.trim() === '') {
            return;
        }

        // Check if any editors are running without API key
        const editors = ['question-description', 'i-question-textarea', 'textarea[name="content"]'];
        let needsReinit = false;

        editors.forEach(selector => {
            const editor = tinymce.get(selector);
            if (editor && !editor.settings.apiKey) {
                console.log('🔄 Re-initializing editor without API key:', selector);
                needsReinit = true;
                editor.destroy();
            }
        });

        if (needsReinit) {
            setTimeout(() => {
                // Re-initialize editors with API key
                if (document.querySelector('.question-description')) {
                    tinymce.init(questionConfig);
                }
                if (document.querySelector('.i-question-textarea')) {
                    tinymce.init(blogConfig);
                }
                if (document.querySelector('textarea[name="content"]')) {
                    tinymce.init(adminConfig);
                }
            }, 200);
        }
    }

    // Set TinyMCE API key if available
    if (window.tinymceApiKey && window.tinymceApiKey.trim() !== '') {
        // Configure TinyMCE with API key globally
        tinymce.init({
            apiKey: window.tinymceApiKey,
            promotion: false, // Hide promotion messages
            branding: false,  // Hide branding
            license_key: 'gpl' // Add GPL license to avoid evaluation mode
        });
        console.log('✅ TinyMCE API key configured for premium features');
        checkApiKeyStatus();
        
        // Force re-initialization of existing editors with API key
        setTimeout(() => {
            if (tinymce.get('.question-description')) {
                tinymce.get('.question-description').destroy();
                tinymce.init(questionConfig);
            }
            if (tinymce.get('.i-question-textarea')) {
                tinymce.get('.i-question-textarea').destroy();
                tinymce.init(blogConfig);
            }
            if (tinymce.get('textarea[name="content"]')) {
                tinymce.get('textarea[name="content"]').destroy();
                tinymce.init(adminConfig);
            }
        }, 100);
        
        // Periodic check to ensure API key is applied
        setInterval(ensureApiKeyApplied, 5000); // Check every 5 seconds
        
        // Add event listeners for modal opening
        document.addEventListener('click', function(e) {
            // Check if modal is being opened
            if (e.target.matches('.aks, .sidebar-ask-btn, .ask-question-btn, .insight-btn, .question-input')) {
                setTimeout(() => {
                    ensureApiKeyApplied();
                }, 500);
            }
        });
        
        // Listen for modal display changes
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                    const modal = mutation.target;
                    if (modal.style.display === 'flex' && (modal.id === 'askModal' || modal.id === 'insightModal')) {
                        setTimeout(() => {
                            ensureApiKeyApplied();
                        }, 300);
                    }
                }
            });
        });
        
        // Observe modal elements
        const askModal = document.getElementById('askModal');
        const insightModal = document.getElementById('insightModal');
        if (askModal) observer.observe(askModal, { attributes: true });
        if (insightModal) observer.observe(insightModal, { attributes: true });
    } else {
        console.log('❌ TinyMCE running in free mode - add API key for premium features');
        checkApiKeyStatus();
    }

    // Register custom plugins
    console.log('Registering custom TinyMCE plugins...');

    tinymce.PluginManager.add('localimage', function (editor) {
        var openDialog = function () {
            var input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.style.display = 'none';
            document.body.appendChild(input);
            input.click();

            input.onchange = function (e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        var dataUrl = e.target.result;
                        editor.insertContent('<img src="' + dataUrl + '" alt="Uploaded Image" style="max-width:100%;height:auto;border-radius:5px;box-shadow:0 2px 8px rgba(0,0,0,0.1);margin:10px 0;" />');
                    };
                    reader.readAsDataURL(file);
                }
                document.body.removeChild(input);
            };
        };

        editor.ui.registry.addButton('localimage', {
            icon: 'upload',
            tooltip: 'Upload Local Image',
            onAction: openDialog
        });

        console.log('Local image plugin registered successfully');

        editor.ui.registry.addMenuItem('localimage', {
            icon: 'image',
            text: 'Upload Local Image',
            onAction: openDialog
        });
    });

    tinymce.PluginManager.add('codehighlight', function (editor) {
        var openDialog = function () {
            return editor.windowManager.open({
                title: 'Insert Code',
                body: {
                    type: 'panel',
                    items: [
                        {
                            type: 'input',
                            name: 'language',
                            label: 'Language',
                            placeholder: 'e.g., javascript, python, php, html, css, java, cpp, csharp, ruby, go, rust, swift, kotlin, typescript, sql, bash, powershell, yaml, json, xml, markdown'
                        },
                        {
                            type: 'textarea',
                            name: 'code',
                            label: 'Code',
                            placeholder: 'Paste your code here...'
                        }
                    ]
                },
                buttons: [
                    {
                        type: 'submit',
                        text: 'Insert'
                    },
                    {
                        type: 'cancel',
                        text: 'Cancel'
                    }
                ],
                onSubmit: function (api) {
                    var data = api.getData();
                    if (data.code && data.language) {
                        var html = '<div class="code-block-wrapper"><pre class="code-block"><code class="language-' + data.language + '">' + editor.dom.encode(data.code) + '</code></pre></div><p><br></p>';
                        editor.insertContent(html);
                        api.close();
                    }
                }
            });
        };

        editor.ui.registry.addButton('codehighlight', {
            icon: 'code-sample',
            tooltip: 'Insert Code Block',
            onAction: openDialog
        });

        console.log('Code highlight plugin registered successfully');

        editor.ui.registry.addMenuItem('codehighlight', {
            icon: 'code',
            text: 'Insert Code Block',
            onAction: openDialog
        });

        editor.on('init', function () {
            editor.dom.addStyle('pre.code-block{position:relative;background:#f4f4f4;border:1px solid #ddd;border-radius:5px;padding:15px;margin:10px 0;overflow-x:auto}pre.code-block code{font-family:\'Courier New\',monospace;font-size:13px;line-height:1.4}pre.code-block .copy-code-btn{position:absolute;top:5px;right:5px;background:#007bff;color:white;border:none;padding:5px 10px;border-radius:3px;cursor:pointer;font-size:12px;opacity:0.8;transition:opacity 0.2s}pre.code-block .copy-code-btn:hover{opacity:1}pre.code-block .copy-code-btn i{margin-right:5px}');
        });
    });
    // Initialize TinyMCE for question description
    if (document.querySelector('.question-description')) {
        console.log('Initializing TinyMCE for question description...');
        const questionConfig = {
            selector: '.question-description',
            height: 200,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
                'codehighlight', 'localimage', 'autosave', 'quickbars'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | localimage | codehighlight | code fullscreen | help',
            menubar: false,
            branding: false,
            promotion: false,
            // Add API key if available
            ...(window.tinymceApiKey && window.tinymceApiKey.trim() !== '' ? { apiKey: window.tinymceApiKey } : {}),
            license_key: 'gpl', // Add GPL license to avoid evaluation mode
            // Premium features
            autosave_interval: '30s',
            autosave_prefix: 'tinymce-autosave-{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '1440m',
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            quickbars_insert_toolbar: false,
            paste_as_text: false,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            content_style: `
                body { 
                    font-family: 'Poppins', sans-serif; 
                    font-size: 14px; 
                    line-height: 1.6; 
                    color: #333; 
                    padding: 10px;
                }
                p { margin: 0 0 10px 0; }
                ul, ol { margin: 0 0 10px 20px; }
                li { margin: 0 0 5px 0; }
                h1, h2, h3, h4, h5, h6 { margin: 0 0 10px 0; font-weight: 600; }
                blockquote { margin: 0 0 10px 0; padding: 10px 15px; border-left: 4px solid #ddd; background: #f9f9f9; }
                code { background: #f4f4f4; padding: 2px 4px; border-radius: 3px; font-family: 'Courier New', monospace; }
                pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
            `,
            setup: function (editor) {
                editor.on('change', function () {
                    // Update the textarea value for form submission
                    editor.save();
                });

                // Add custom paste handling
                editor.on('PastePreProcess', function (e) {
                    // Clean up pasted content
                    e.content = e.content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                });
            },
            init_instance_callback: function (editor) {
                console.log('TinyMCE question description editor initialized successfully');

                // Handle content changes to highlight code
                editor.on('change keyup', function () {
                    setTimeout(function () {
                        if (typeof highlightCodeAfterLoad === 'function') {
                            highlightCodeAfterLoad();
                        }
                    }, 100);
                });
            }
        };
        
        tinymce.init(questionConfig);
    }

    // Initialize TinyMCE for blog post content
    if (document.querySelector('.i-question-textarea')) {
        console.log('Initializing TinyMCE for blog post content...');
        const blogConfig = {
            selector: '.i-question-textarea',
            height: 400,
            // Add API key if available
            ...(window.tinymceApiKey && window.tinymceApiKey.trim() !== '' ? { apiKey: window.tinymceApiKey } : {}),
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
                'codehighlight', 'localimage', 'autosave', 'quickbars', 'powerpaste'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | localimage | media | codehighlight | code fullscreen | help',
            menubar: false,
            branding: false,
            promotion: false,
            // Premium features
            spellchecker_language: 'en',
            spellchecker_rpc_url: 'https://spellchecker.tiny.cloud',
            autosave_interval: '30s',
            autosave_prefix: 'tinymce-autosave-{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '1440m',
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            quickbars_insert_toolbar: false,
            powerpaste_word_import: 'clean',
            powerpaste_html_import: 'clean',
            paste_as_text: false,
            paste_enable_default_filters: true,
            paste_word_valid_elements: "b,strong,i,em,h1,h2,h3,h4,h5,h6,p,br,ul,ol,li,blockquote,pre,code",
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            content_style: `
                body { 
                    font-family: 'Poppins', sans-serif; 
                    font-size: 14px; 
                    line-height: 1.6; 
                    color: #333; 
                    padding: 10px;
                }
                p { margin: 0 0 10px 0; }
                ul, ol { margin: 0 0 10px 20px; }
                li { margin: 0 0 5px 0; }
                h1, h2, h3, h4, h5, h6 { margin: 0 0 10px 0; font-weight: 600; }
                blockquote { margin: 0 0 10px 0; padding: 10px 15px; border-left: 4px solid #ddd; background: #f9f9f9; }
                code { background: #f4f4f4; padding: 2px 4px; border-radius: 3px; font-family: 'Courier New', monospace; }
                pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
            `,
            setup: function (editor) {
                editor.on('change', function () {
                    // Update the textarea value for form submission
                    editor.save();
                });

                // Add custom paste handling
                editor.on('PastePreProcess', function (e) {
                    // Clean up pasted content
                    e.content = e.content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                });
            },
            init_instance_callback: function (editor) {
                console.log('TinyMCE blog post editor initialized successfully');

                // Handle content changes to highlight code
                editor.on('change keyup', function () {
                    setTimeout(function () {
                        if (typeof highlightCodeAfterLoad === 'function') {
                            highlightCodeAfterLoad();
                        }
                    }, 100);
                });
            }
        };
        
        tinymce.init(blogConfig);
    }

    // Initialize TinyMCE for admin post content
    if (document.querySelector('textarea[name="content"]')) {
        console.log('Initializing TinyMCE for admin post content...');
        const adminConfig = {
            selector: 'textarea[name="content"]',
            height: 400,
            // Add API key if available
            ...(window.tinymceApiKey && window.tinymceApiKey.trim() !== '' ? { apiKey: window.tinymceApiKey } : {}),
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
                'codehighlight', 'localimage', 'autosave', 'quickbars', 'powerpaste'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image localimage media | codehighlight | code fullscreen | help',
            menubar: false,
            branding: false,
            promotion: false,
            // Premium features
            spellchecker_language: 'en',
            spellchecker_rpc_url: 'https://spellchecker.tiny.cloud',
            autosave_interval: '30s',
            autosave_prefix: 'tinymce-autosave-{path}{query}-{id}-',
            autosave_restore_when_empty: false,
            autosave_retention: '1440m',
            quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
            quickbars_insert_toolbar: false,
            powerpaste_word_import: 'clean',
            powerpaste_html_import: 'clean',
            paste_as_text: false,
            paste_enable_default_filters: true,
            paste_word_valid_elements: "b,strong,i,em,h1,h2,h3,h4,h5,h6,p,br,ul,ol,li,blockquote,pre,code",
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            content_style: `
                body { 
                    font-family: 'Poppins', sans-serif; 
                    font-size: 14px; 
                    line-height: 1.6; 
                    color: #333; 
                    padding: 10px;
                }
                p { margin: 0 0 10px 0; }
                ul, ol { margin: 0 0 10px 20px; }
                li { margin: 0 0 5px 0; }
                h1, h2, h3, h4, h5, h6 { margin: 0 0 10px 0; font-weight: 600; }
                blockquote { margin: 0 0 10px 0; padding: 10px 15px; border-left: 4px solid #ddd; background: #f9f9f9; }
                code { background: #f4f4f4; padding: 2px 4px; border-radius: 3px; font-family: 'Courier New', monospace; }
                pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
            `,
            setup: function (editor) {
                editor.on('change', function () {
                    // Update the textarea value for form submission
                    editor.save();
                });

                // Add custom paste handling
                editor.on('PastePreProcess', function (e) {
                    // Clean up pasted content
                    e.content = e.content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                });
            },
            init_instance_callback: function (editor) {
                console.log('TinyMCE admin editor initialized successfully');

                // Handle content changes to highlight code
                editor.on('change keyup', function () {
                    setTimeout(function () {
                        if (typeof highlightCodeAfterLoad === 'function') {
                            highlightCodeAfterLoad();
                        }
                    }, 100);
                });
            }
        };
        
        tinymce.init(adminConfig);
    }
});

// Function to get content from TinyMCE editor
function getEditorContent(selector) {
    if (tinymce.get(selector)) {
        return tinymce.get(selector).getContent();
    }
    return document.querySelector(selector).value;
}

// Function to set content in TinyMCE editor
function setEditorContent(selector, content) {
    if (tinymce.get(selector)) {
        tinymce.get(selector).setContent(content);
    } else {
        document.querySelector(selector).value = content;
    }
}

// Function to destroy TinyMCE editor
function destroyEditor(selector) {
    if (tinymce.get(selector)) {
        tinymce.get(selector).destroy();
    }
}

// Global function to reinitialize editors with API key
window.initializeTinyMCE = function() {
    if (window.tinymceApiKey && window.tinymceApiKey.trim() !== '' && typeof tinymce !== 'undefined') {
        console.log('🔄 Re-initializing editors with API key');
        
        // Re-initialize question description editor
        if (document.querySelector('.question-description')) {
            if (tinymce.get('.question-description')) {
                tinymce.get('.question-description').destroy();
            }
            setTimeout(() => {
                tinymce.init(questionConfig);
            }, 100);
        }
        
        // Re-initialize blog post editor
        if (document.querySelector('.i-question-textarea')) {
            if (tinymce.get('.i-question-textarea')) {
                tinymce.get('.i-question-textarea').destroy();
            }
            setTimeout(() => {
                tinymce.init(blogConfig);
            }, 100);
        }
        
        // Re-initialize admin editor
        if (document.querySelector('textarea[name="content"]')) {
            if (tinymce.get('textarea[name="content"]')) {
                tinymce.get('textarea[name="content"]').destroy();
            }
            setTimeout(() => {
                tinymce.init(adminConfig);
            }, 100);
        }
    }
};

// Test function to check API key status
window.testTinyMCEApiKey = function() {
    console.log('🔍 Testing TinyMCE API key status...');
    console.log('API Key available:', !!window.tinymceApiKey);
    console.log('API Key length:', window.tinymceApiKey ? window.tinymceApiKey.length : 0);
    
    const editors = ['question-description', 'i-question-textarea', 'textarea[name="content"]'];
    editors.forEach(selector => {
        const editor = tinymce.get(selector);
        if (editor) {
            console.log(`Editor ${selector}:`, {
                hasApiKey: !!editor.settings.apiKey,
                hasLicenseKey: !!editor.settings.license_key,
                promotion: editor.settings.promotion,
                branding: editor.settings.branding
            });
        }
    });
    
    // Check for "Get All Features" button
    const getFeaturesBtn = document.querySelector('.tox-promotion-link');
    console.log('"Get All Features" button found:', !!getFeaturesBtn);
    
    return {
        apiKeyAvailable: !!window.tinymceApiKey,
        editorsWithApiKey: editors.filter(selector => {
            const editor = tinymce.get(selector);
            return editor && editor.settings.apiKey;
        }).length,
        getFeaturesButtonFound: !!getFeaturesBtn
    };
};

// Helper function to create TinyMCE configuration with premium features
function createTinyMCEConfig(selector, customConfig = {}) {
    const baseConfig = {
        selector: selector,
        height: 400,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
            'codehighlight', 'localimage', 'spellchecker', 'autosave', 'quickbars', 'powerpaste'
        ],
        toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | localimage | media | codehighlight | spellchecker | code fullscreen | help',
        menubar: false,
        branding: false,
        promotion: false,
        // Premium features
        spellchecker_language: 'en',
        spellchecker_rpc_url: 'https://spellchecker.tiny.cloud',
        autosave_interval: '30s',
        autosave_prefix: 'tinymce-autosave-{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '1440m',
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        quickbars_insert_toolbar: false,
        powerpaste_word_import: 'clean',
        powerpaste_html_import: 'clean',
        paste_as_text: false,
        paste_enable_default_filters: true,
        paste_word_valid_elements: "b,strong,i,em,h1,h2,h3,h4,h5,h6,p,br,ul,ol,li,blockquote,pre,code",
        relative_urls: false,
        remove_script_host: false,
        convert_urls: false,
        content_style: `
            body { 
                font-family: 'Poppins', sans-serif; 
                font-size: 14px; 
                line-height: 1.6; 
                color: #333; 
                padding: 10px;
            }
            p { margin: 0 0 10px 0; }
            ul, ol { margin: 0 0 10px 20px; }
            li { margin: 0 0 5px 0; }
            h1, h2, h3, h4, h5, h6 { margin: 0 0 10px 0; font-weight: 600; }
            blockquote { margin: 0 0 10px 0; padding: 10px 15px; border-left: 4px solid #ddd; background: #f9f9f9; }
            code { background: #f4f4f4; padding: 2px 4px; border-radius: 3px; font-family: 'Courier New', monospace; }
            pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
        `,
        setup: function (editor) {
            editor.on('change', function () {
                // Update the textarea value for form submission
                editor.save();
            });

            // Add custom paste handling
            editor.on('PastePreProcess', function (e) {
                // Clean up pasted content
                e.content = e.content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
            });
        },
        init_instance_callback: function (editor) {
            console.log('TinyMCE editor initialized successfully');

            // Handle content changes to highlight code
            editor.on('change keyup', function () {
                setTimeout(function () {
                    if (typeof highlightCodeAfterLoad === 'function') {
                        highlightCodeAfterLoad();
                    }
                }, 100);
            });
        }
    };

    // Merge custom configuration
    return { ...baseConfig, ...customConfig };
} 