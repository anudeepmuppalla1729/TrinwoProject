<?php

return [
    /*
    |--------------------------------------------------------------------------
    | TinyMCE API Key
    |--------------------------------------------------------------------------
    |
    | Your TinyMCE API key for premium features during the 14-day trial.
    | Get your API key from: https://www.tiny.cloud/auth/signup/
    |
    */
    'api_key' => env('TINYMCE_API_KEY', ''),

    /*
    |--------------------------------------------------------------------------
    | TinyMCE Configuration
    |--------------------------------------------------------------------------
    |
    | Default configuration for TinyMCE editors across the application.
    |
    */
    'default_config' => [
        'height' => 400,
        'plugins' => [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount', 'paste',
            'codehighlight', 'localimage'
        ],
        'toolbar' => 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | localimage | media | codehighlight | code fullscreen | help',
        'menubar' => false,
        'branding' => false,
        'promotion' => false,
        'paste_as_text' => false,
        'paste_enable_default_filters' => true,
        'paste_word_valid_elements' => "b,strong,i,em,h1,h2,h3,h4,h5,h6,p,br,ul,ol,li,blockquote,pre,code",
        'relative_urls' => false,
        'remove_script_host' => false,
        'convert_urls' => false,
        'content_style' => "
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
        ",
    ],

    /*
    |--------------------------------------------------------------------------
    | Question Description Editor Config
    |--------------------------------------------------------------------------
    */
    'question_description' => [
        'height' => 200,
        'toolbar' => 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | localimage | codehighlight | code fullscreen | help',
    ],

    /*
    |--------------------------------------------------------------------------
    | Blog Post Editor Config
    |--------------------------------------------------------------------------
    */
    'blog_post' => [
        'height' => 400,
        'toolbar' => 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | localimage | media | codehighlight | code fullscreen | help',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Post Editor Config
    |--------------------------------------------------------------------------
    */
    'admin_post' => [
        'height' => 400,
        'toolbar' => 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image localimage media | codehighlight | code fullscreen | help',
    ],
]; 