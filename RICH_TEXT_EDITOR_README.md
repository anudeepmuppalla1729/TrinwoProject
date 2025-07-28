# Rich Text Editor Feature

This document describes the rich text editor implementation in the Q&A Forum website.

## Overview

The website now includes a TinyMCE rich text editor for enhanced content creation. The editor is integrated into:

1. **Question Descriptions** - When creating new questions
2. **Blog Post Content** - When creating new blog posts
3. **Admin Post Management** - In the admin panel for post creation

## Features

### Editor Capabilities
- **Text Formatting**: Bold, italic, underline, strikethrough
- **Text Alignment**: Left, center, right, justify
- **Lists**: Bullet points and numbered lists
- **Links**: Add and edit hyperlinks
- **Images**: Insert and manage images (URL and local upload)
- **Code Blocks**: Syntax highlighting with copy functionality
- **Local Image Upload**: Upload images directly from your computer
- **Fullscreen Mode**: Expand editor for better writing experience
- **Word Count**: Track content length
- **Paste Handling**: Smart paste with content cleaning

### Security Features
- **XSS Protection**: Automatic script tag removal
- **Content Filtering**: Whitelist of allowed HTML elements
- **Paste Sanitization**: Clean pasted content from external sources

## Implementation Details

### Files Added/Modified

#### New Files
- `public/js/rich-text-editor.js` - Main editor configuration
- `public/css/rich-text-editor.css` - Editor styling
- `public/js/code-highlighting.js` - Code highlighting and copy functionality
- `public/css/code-highlighting.css` - Code highlighting styles
- `public/tinymce/plugins/codehighlight/plugin.min.js` - Custom code highlighting plugin
- `public/tinymce/plugins/localimage/plugin.min.js` - Custom local image upload plugin
- `RICH_TEXT_EDITOR_README.md` - This documentation

#### Modified Files
- `resources/views/layouts/app.blade.php` - Added editor scripts and styles
- `resources/views/layouts/admin.blade.php` - Added editor scripts and styles
- `public/js/global.js` - Updated form submission to work with TinyMCE
- `resources/views/pages/question.blade.php` - Updated to render HTML content
- `package.json` - Added TinyMCE dependencies

### Dependencies
- `tinymce` - Core TinyMCE library (self-hosted)
- `prismjs` - Syntax highlighting library
- `@tinymce/tinymce-vue` - Vue.js integration (for future use)

### Hosting
The TinyMCE editor is self-hosted locally in the `public/tinymce/` directory, eliminating the need for API keys or external dependencies.

## Usage

### For Users

#### Creating Questions
1. Click "Ask Question" button
2. Fill in the question title
3. Use the rich text editor for the description
4. Add tags and submit

#### Creating Blog Posts
1. Click "Create Blog Post" button
2. Fill in the post title
3. Use the rich text editor for the content
4. **For Code Blocks**: Click the "Code Block" button (blue icon) to insert syntax-highlighted code
5. **For Local Images**: Click the "Upload Local Image" button (green upload icon) to select images from your computer
6. **For URL Images**: Click the regular "Image" button to insert images from URLs
7. Add cover image (optional)
8. Submit the post

### For Developers

#### Adding Rich Text Editor to New Forms

1. **Add the textarea with appropriate class:**
```html
<textarea class="question-description" name="description"></textarea>
```

2. **Include the editor scripts in your layout:**
```html
<script src="{{ asset('js/rich-text-editor.js') }}"></script>
<script src="{{ asset('js/code-highlighting.js') }}"></script>
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>
<script src="{{ asset('prismjs/prism.js') }}"></script>
<!-- Include language-specific Prism.js components as needed -->
```

3. **Include the editor styles:**
```html
<link rel="stylesheet" href="{{ asset('css/rich-text-editor.css') }}">
<link rel="stylesheet" href="{{ asset('css/code-highlighting.css') }}">
```

4. **Update form submission to get content from editor:**
```javascript
let content = '';
if (tinymce.get('.question-description')) {
    content = tinymce.get('.question-description').getContent();
} else {
    content = document.querySelector('.question-description').value;
}
```

#### Displaying HTML Content
Use the `{!! !!}` syntax to render HTML content:
```php
{!! $content !!}
```

## Configuration

### Editor Settings
The editor configuration can be found in `public/js/rich-text-editor.js`. Key settings include:

- **Height**: 300px for questions, 400px for blog posts
- **Toolbar**: Customized for different use cases
- **Content Style**: Matches website design
- **Security**: XSS protection and content filtering

### Customization
To customize the editor:

1. **Add new toolbar buttons:**
```javascript
toolbar: 'undo redo | bold italic | your-custom-button'
```

2. **Add new plugins:**
```javascript
plugins: ['advlist', 'autolink', 'your-custom-plugin']
```

3. **Modify content styling:**
```javascript
content_style: `
    body { 
        font-family: 'Your-Font', sans-serif; 
        // ... other styles
    }
`
```

## Security Considerations

### Content Sanitization
- All pasted content is automatically cleaned
- Script tags are removed
- Only whitelisted HTML elements are allowed

### XSS Protection
- TinyMCE includes built-in XSS protection
- Custom paste handlers remove malicious scripts
- Content is validated on both client and server side

## Browser Support
- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Troubleshooting

### Common Issues

1. **Editor not loading:**
   - Check if TinyMCE script is loaded
   - Verify selector matches textarea class
   - Check browser console for errors

2. **Content not saving:**
   - Ensure `editor.save()` is called
   - Check form submission logic
   - Verify content is properly extracted

3. **Styling issues:**
   - Check if CSS file is loaded
   - Verify CSS specificity
   - Test in different browsers

4. **Local Image Upload not working:**
   - Look for the green "Upload" button in the toolbar (separate from the regular image button)
   - Check browser console for "Local image plugin registered successfully" message
   - Ensure you're clicking the correct button (green upload icon, not the regular image icon)
   - Verify that your browser supports FileReader API
   - Check if any browser extensions are blocking file access

5. **Modal scrolling issues:**
   - The modal now supports vertical scrolling when content exceeds the viewport height
   - Maximum modal height is set to 90vh (90% of viewport height) on desktop and 85vh on mobile
   - TinyMCE editor within modals has a maximum height of 300px with internal scrolling
   - Body scroll is automatically disabled when modal is open to prevent background scrolling

6. **Code highlighting not working:**
   - Look for the blue "Code Block" button in the toolbar
   - Check browser console for "Code highlight plugin registered successfully" message
   - Ensure Prism.js scripts are loaded correctly
   - Verify that the language specified is supported by Prism.js

### Debug Mode
Enable debug mode by adding to editor configuration:
```javascript
debug: true
```

## Code Highlighting Features

### Supported Languages
- **Python** - Full syntax highlighting with keywords, strings, comments
- **JavaScript** - ES6+ support with modern syntax
- **PHP** - Server-side scripting language
- **Java** - Object-oriented programming
- **C++** - System programming language
- **C#** - Microsoft .NET framework
- **Ruby** - Dynamic programming language
- **Go** - Google's programming language
- **Rust** - Systems programming language
- **Swift** - Apple's programming language
- **Kotlin** - Android development
- **TypeScript** - Typed JavaScript
- **SQL** - Database queries
- **Bash** - Shell scripting
- **PowerShell** - Windows automation
- **YAML** - Configuration files
- **JSON** - Data interchange format
- **XML** - Markup language
- **Markdown** - Documentation format

### Copy Functionality
- **One-click Copy**: Copy code with a single click
- **Visual Feedback**: Success/error indicators
- **Modern API**: Uses Clipboard API with fallback
- **Cross-browser**: Works in all modern browsers

### Local Image Upload
- **Direct Upload**: Upload images from your computer using the green upload button
- **Base64 Encoding**: Images are embedded directly in content as base64 data
- **No Server Storage**: Images are stored in the content itself, no server upload required
- **Instant Preview**: See images immediately after upload
- **File Type Support**: Supports all common image formats (JPG, PNG, GIF, WebP, etc.)
- **Automatic Styling**: Images are automatically styled with rounded corners and shadows

## Future Enhancements

- **Server Image Upload**: Upload images to server storage
- **Auto-save**: Automatic content saving
- **Collaborative Editing**: Real-time collaboration
- **Custom Plugins**: Site-specific functionality
- **Mobile Optimization**: Better mobile experience
- **Code Execution**: Run code snippets directly
- **Version Control**: Track content changes

## Support

For issues or questions about the rich text editor implementation, please refer to:
- TinyMCE Documentation: https://www.tiny.cloud/docs/
- Laravel Documentation: https://laravel.com/docs
- Project Issues: [Your project repository] 