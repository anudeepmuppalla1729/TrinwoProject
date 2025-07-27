// Rich Text Editor Configuration
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Starting TinyMCE initialization...');
    
    // Check if TinyMCE is available
    if (typeof tinymce === 'undefined') {
        console.error('TinyMCE is not loaded. Please check if the script is included correctly.');
        console.error('TinyMCE script should be loaded before rich-text-editor.js');
        return;
    }
    
    console.log('TinyMCE is available, version:', tinymce.majorVersion + '.' + tinymce.minorVersion);
    
    // Register custom plugins
    console.log('Registering custom TinyMCE plugins...');
    
    tinymce.PluginManager.add('localimage', function(editor) {
        var openDialog = function() {
            var input = document.createElement('input');
            input.type = 'file';
            input.accept = 'image/*';
            input.style.display = 'none';
            document.body.appendChild(input);
            input.click();
            
            input.onchange = function(e) {
                var file = e.target.files[0];
                if (file) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
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
    
    tinymce.PluginManager.add('codehighlight', function(editor) {
        var openDialog = function() {
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
                onSubmit: function(api) {
                    var data = api.getData();
                    if (data.code && data.language) {
                        var html = '<pre class="code-block"><code class="language-' + data.language + '">' + editor.dom.encode(data.code) + '</code><button class="copy-code-btn" onclick="copyCode(this)"><i class="fas fa-copy"></i> Copy</button></pre>';
                        editor.insertContent(html);
                        api.close();
                    }
                }
            });
        };
        
        editor.ui.registry.addButton('codehighlight', {
            text: 'Code',
            tooltip: 'Insert Code Block',
            onAction: openDialog
        });
        
        console.log('Code highlight plugin registered successfully');
        
        editor.ui.registry.addMenuItem('codehighlight', {
            icon: 'code',
            text: 'Insert Code Block',
            onAction: openDialog
        });
        
        editor.on('init', function() {
            editor.dom.addStyle('pre.code-block{position:relative;background:#f4f4f4;border:1px solid #ddd;border-radius:5px;padding:15px;margin:10px 0;overflow-x:auto}pre.code-block code{font-family:\'Courier New\',monospace;font-size:13px;line-height:1.4}pre.code-block .copy-code-btn{position:absolute;top:5px;right:5px;background:#007bff;color:white;border:none;padding:5px 10px;border-radius:3px;cursor:pointer;font-size:12px;opacity:0.8;transition:opacity 0.2s}pre.code-block .copy-code-btn:hover{opacity:1}pre.code-block .copy-code-btn i{margin-right:5px}');
        });
    });
    
    // Debug plugin registration
    console.log('TinyMCE version:', tinymce.majorVersion + '.' + tinymce.minorVersion);
    console.log('Available plugins after registration:', Object.keys(tinymce.PluginManager.plugins));
    console.log('Checking if codehighlight plugin is registered:', 'codehighlight' in tinymce.PluginManager.plugins);
    
    // Wait a bit for plugins to be fully registered
    setTimeout(function() {
        console.log('Final plugin check:', Object.keys(tinymce.PluginManager.plugins));
    }, 100);
    
    // Initialize TinyMCE for question description
    console.log('Looking for .question-description element...');
    const questionDescElement = document.querySelector('.question-description');
    console.log('Question description element found:', questionDescElement);
    
    if (questionDescElement) {
        console.log('Initializing TinyMCE for question description...');
        tinymce.init({
            selector: '.question-description',
            height: 300,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
                'codehighlight', 'localimage'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | localimage | codehighlight | code fullscreen | help',
            menubar: false,
            branding: false,
            promotion: false,
            paste_as_text: false,
            paste_enable_default_filters: true,
            paste_word_valid_elements: "b,strong,i,em,h1,h2,h3,h4,h5,h6,p,br,ul,ol,li",
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
            setup: function(editor) {
                editor.on('change', function() {
                    // Update the textarea value for form submission
                    editor.save();
                });
                
                // Add custom paste handling
                editor.on('PastePreProcess', function(e) {
                    // Clean up pasted content
                    e.content = e.content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                });
            },
            init_instance_callback: function(editor) {
                console.log('TinyMCE question description editor initialized successfully');
                
                // Handle content changes to highlight code
                editor.on('change keyup', function() {
                    setTimeout(function() {
                        if (typeof highlightCodeAfterLoad === 'function') {
                            highlightCodeAfterLoad();
                        }
                    }, 100);
                });
            }
        });
    }

    // Initialize TinyMCE for blog post content
    console.log('Looking for .i-question-textarea element...');
    const blogTextareaElement = document.querySelector('.i-question-textarea');
    console.log('Blog textarea element found:', blogTextareaElement);
    
    if (blogTextareaElement) {
        console.log('Initializing TinyMCE for blog post content...');
        tinymce.init({
            selector: '.i-question-textarea',
            height: 400,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
                'codehighlight', 'localimage'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | localimage | media | codehighlight | code fullscreen | help',
            menubar: false,
            branding: false,
            promotion: false,
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
            setup: function(editor) {
                editor.on('change', function() {
                    // Update the textarea value for form submission
                    editor.save();
                });
                
                // Add custom paste handling
                editor.on('PastePreProcess', function(e) {
                    // Clean up pasted content
                    e.content = e.content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                });
            },
            init_instance_callback: function(editor) {
                console.log('TinyMCE blog post editor initialized successfully');
                
                // Handle content changes to highlight code
                editor.on('change keyup', function() {
                    setTimeout(function() {
                        if (typeof highlightCodeAfterLoad === 'function') {
                            highlightCodeAfterLoad();
                        }
                    }, 100);
                });
            }
        });
    }

    // Initialize TinyMCE for admin post content
    if (document.querySelector('textarea[name="content"]')) {
        console.log('Initializing TinyMCE for admin post content...');
        tinymce.init({
            selector: 'textarea[name="content"]',
            height: 400,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
                'codehighlight', 'localimage'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image localimage media | codehighlight | code fullscreen | help',
            menubar: false,
            branding: false,
            promotion: false,
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
            setup: function(editor) {
                editor.on('change', function() {
                    // Update the textarea value for form submission
                    editor.save();
                });
                
                // Add custom paste handling
                editor.on('PastePreProcess', function(e) {
                    // Clean up pasted content
                    e.content = e.content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                });
            },
            init_instance_callback: function(editor) {
                console.log('TinyMCE admin editor initialized successfully');
                
                // Handle content changes to highlight code
                editor.on('change keyup', function() {
                    setTimeout(function() {
                        if (typeof highlightCodeAfterLoad === 'function') {
                            highlightCodeAfterLoad();
                        }
                    }, 100);
                });
            }
        });
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

// Function to initialize TinyMCE for dynamically created elements
function initializeTinyMCEForElement(selector) {
    console.log('Initializing TinyMCE for element:', selector);
    
    if (selector === '.question-description') {
        if (tinymce.get(selector)) {
            tinymce.get(selector).destroy();
        }
        
        tinymce.init({
            selector: selector,
            height: 300,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
                'codehighlight', 'localimage'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | localimage | codehighlight | code fullscreen | help',
            menubar: false,
            branding: false,
            promotion: false,
            paste_as_text: false,
            paste_enable_default_filters: true,
            paste_word_valid_elements: "b,strong,i,em,h1,h2,h3,h4,h5,h6,p,br,ul,ol,li",
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
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
                
                editor.on('PastePreProcess', function(e) {
                    e.content = e.content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                });
            },
            init_instance_callback: function(editor) {
                console.log('TinyMCE question description editor initialized successfully');
                
                editor.on('change keyup', function() {
                    setTimeout(function() {
                        if (typeof highlightCodeAfterLoad === 'function') {
                            highlightCodeAfterLoad();
                        }
                    }, 100);
                });
            }
        });
    } else if (selector === '.i-question-textarea') {
        if (tinymce.get(selector)) {
            tinymce.get(selector).destroy();
        }
        
        tinymce.init({
            selector: selector,
            height: 400,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
                'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
                'codehighlight', 'localimage'
            ],
            toolbar: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | localimage | media | codehighlight | code fullscreen | help',
            menubar: false,
            branding: false,
            promotion: false,
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
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
                
                editor.on('PastePreProcess', function(e) {
                    e.content = e.content.replace(/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/gi, '');
                });
            },
            init_instance_callback: function(editor) {
                console.log('TinyMCE blog post editor initialized successfully');
                
                editor.on('change keyup', function() {
                    setTimeout(function() {
                        if (typeof highlightCodeAfterLoad === 'function') {
                            highlightCodeAfterLoad();
                        }
                    }, 100);
                });
            }
        });
    }
}

// Make the function globally available
window.initializeTinyMCEForElement = initializeTinyMCEForElement; 