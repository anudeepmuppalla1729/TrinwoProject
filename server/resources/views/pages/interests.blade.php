<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Interests</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4edf5 100%);
            padding: 0;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .container {
            background: rgba(255, 255, 255, 0.95);
            padding: 2rem;
            border-radius: 16px;
            width: 95%;
            max-width: 1000px;
            position: relative;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin: 2rem 0;
        }

        /* Corner decorations */
        .corner {
            position: absolute;
            width: 80px;
            height: 80px;
            background-size: contain;
            background-repeat: no-repeat;
            pointer-events: none;
            z-index: 1;
        }

        .top-left {
            top: 0;
            left: 0;
            background: linear-gradient(135deg, transparent 0%, transparent 50%, #c92ae0 50%, #c92ae0 100%);
            border-radius: 0 0 100% 0;
        }

        .top-right {
            top: 0;
            right: 0;
            background: linear-gradient(225deg, transparent 0%, transparent 50%, #c92ae0 50%, #c92ae0 100%);
            border-radius: 0 0 0 100%;
        }

        .bottom-left {
            bottom: 0;
            left: 0;
            background: linear-gradient(45deg, transparent 0%, transparent 50%, #c92ae0 50%, #c92ae0 100%);
            border-radius: 0 100% 0 0;
        }

        .bottom-right {
            bottom: 0;
            right: 0;
            background: linear-gradient(315deg, transparent 0%, transparent 50%, #c92ae0 50%, #c92ae0 100%);
            border-radius: 100% 0 0 0;
        }

        h1 {
            background: #c92ae0;
            color: white;
            padding: 0.8rem 2rem;
            border-radius: 50px;
            display: inline-block;
            font-size: 1.8rem;
            margin: 1.5rem 0 2.5rem;
            position: relative;
            z-index: 2;
            box-shadow: 0 4px 15px rgba(201, 42, 224, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .instructions {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1.1rem;
            position: relative;
            z-index: 2;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            padding: 1rem;
            position: relative;
            z-index: 2;
        }

        .interest-box {
            position: relative;
            border: 3px solid #e0e0e0;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            background: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            height: 220px;
            display: flex;
            flex-direction: column;
        }

        .interest-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            border-color: #c92ae0;
        }

        .interest-box.selected {
            border-color: #c92ae0;
            box-shadow: 0 0 0 3px rgba(201, 42, 224, 0.5);
            transform: scale(1.03);
        }

        .interest-box.selected::after {
            content: '\f00c';
            font-family: 'Font Awesome 5 Free';
            font-weight: 900;
            position: absolute;
            top: 10px;
            right: 10px;
            width: 28px;
            height: 28px;
            background: #c92ae0;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .interest-img {
            flex-grow: 1;
            overflow: hidden;
        }

        .interest-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .interest-box:hover img {
            transform: scale(1.1);
        }

        .interest-title {
            background: rgba(0, 0, 0, 0.7);
            padding: 0.8rem;
            font-weight: bold;
            color: white;
            font-size: 1.3rem;
            text-align: center;
            transition: background 0.3s ease;
        }

        .interest-box:hover .interest-title {
            background: #c92ae0;
        }

        .buttons {
            margin-top: 2.5rem;
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            position: relative;
            z-index: 2;
        }

        button {
            padding: 0.8rem 2.5rem;
            border: none;
            border-radius: 50px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .skip-btn {
            background: white;
            color: #777;
            border: 2px solid #e0e0e0;
        }

        .skip-btn:hover {
            background: #f5f5f5;
            color: #333;
            border-color: #ccc;
        }

        .continue-btn {
            background: linear-gradient(to right, #c92ae0, #a020f0);
            color: white;
        }

        .continue-btn:hover {
            background: linear-gradient(to right, #b324c9, #8c1bd1);
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(201, 42, 224, 0.4);
        }

        .continue-btn:disabled {
            background: #cccccc;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .selected-count {
            margin-top: 1.5rem;
            font-weight: 500;
            color: #c92ae0;
            font-size: 1.1rem;
            position: relative;
            z-index: 2;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
            }
            
            h1 {
                font-size: 1.5rem;
                padding: 0.7rem 1.5rem;
            }
            
            .grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 1rem;
            }
            
            .interest-box {
                height: 180px;
            }
            
            .interest-title {
                font-size: 1.1rem;
                padding: 0.6rem;
            }
            
            button {
                padding: 0.7rem 1.8rem;
                font-size: 1rem;
            }
        }

        @media (max-width: 480px) {
            .container {
                padding: 1.2rem;
            }
            
            .grid {
                grid-template-columns: 1fr 1fr;
            }
            
            .buttons {
                flex-direction: column;
                gap: 0.8rem;
            }
            
            button {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="corner top-left"></div>
        <div class="corner top-right"></div>
        <div class="corner bottom-left"></div>
        <div class="corner bottom-right"></div>
        
        <h1>Select Your Interests</h1>
        <p class="instructions">Choose topics you're passionate about to personalize your experience</p>
        
        <div class="grid">
            <!-- Interest Boxes -->
            <div class="interest-box" data-interest="education">
                <div class="interest-img">
                    <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Education">
                </div>
                <div class="interest-title">Education</div>
            </div>
            
            <div class="interest-box" data-interest="food">
                <div class="interest-img">
                    <img src="https://images.unsplash.com/photo-1490818387583-1baba5e638af?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Food">
                </div>
                <div class="interest-title">Food</div>
            </div>
            
            <div class="interest-box" data-interest="history">
                <div class="interest-img">
                    <img src="https://images.unsplash.com/photo-1585208798174-6cedd86e019a?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="History">
                </div>
                <div class="interest-title">History</div>
            </div>
            
            <div class="interest-box" data-interest="technology">
                <div class="interest-img">
                    <img src="https://images.unsplash.com/photo-1518770660439-4636190af475?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Technology">
                </div>
                <div class="interest-title">Technology</div>
            </div>
            
            <div class="interest-box" data-interest="cooking">
                <div class="interest-img">
                    <img src="https://images.unsplash.com/photo-1504674900247-0877df9cc836?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Cooking">
                </div>
                <div class="interest-title">Cooking</div>
            </div>
            
            
            <div class="interest-box" data-interest="sports">
                <div class="interest-img">
                    <img src="https://images.unsplash.com/photo-1547347298-4074fc3086f0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Sports">
                </div>
                <div class="interest-title">Sports</div>
            </div>
            
            <div class="interest-box" data-interest="art">
                <div class="interest-img">
                    <img src="https://images.unsplash.com/photo-1579783902614-a3fb3927b6a5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Art">
                </div>
                <div class="interest-title">Art</div>
            </div>
            
            <div class="interest-box" data-interest="movies">
                <div class="interest-img">
                    <img src="https://images.unsplash.com/photo-1542204165-65bf26472b9b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=500&q=80" alt="Movies">
                </div>
                <div class="interest-title">Movies</div>
            </div>
        </div>
        
        <div class="selected-count">Selected: <span id="count">0</span> interests</div>
        
        <div class="buttons">
            <button class="skip-btn"><i class="fas fa-forward"></i> Skip</button>
            <button class="continue-btn" id="continueBtn" disabled><i class="fas fa-check-circle"></i> Continue</button>
        </div>
    </div>

    <div id="skip-confirm-modal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.3); align-items:center; justify-content:center; z-index:9999;">
        <div class="modal-content" style="background:#fff; padding:32px 24px; border-radius:8px; max-width:350px; text-align:center; box-shadow:0 2px 16px rgba(0,0,0,0.15);">
            <h3 style="margin-bottom:16px;">Skip Interest Selection?</h3>
            <p style="margin-bottom:24px;">You can update your preferences later in settings. Are you sure you want to skip?</p>
            <button id="confirmSkipBtn" class="btn btn-primary" style="margin-right:12px;">Yes, Skip</button>
            <button id="cancelSkipBtn" class="btn btn-outline">Cancel</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const interestBoxes = document.querySelectorAll('.interest-box');
            const continueBtn = document.getElementById('continueBtn');
            const countElement = document.getElementById('count');
            let selectedInterests = [];
            
            // Add click event to each interest box
            interestBoxes.forEach(box => {
                box.addEventListener('click', function() {
                    const interest = this.getAttribute('data-interest');
                    
                    // Toggle selection
                    if (this.classList.contains('selected')) {
                        this.classList.remove('selected');
                        selectedInterests = selectedInterests.filter(item => item !== interest);
                    } else {
                        this.classList.add('selected');
                        selectedInterests.push(interest);
                    }
                    
                    // Update UI
                    updateSelectionUI();
                });
            });
            
            // Skip button functionality
            document.querySelector('.skip-btn').addEventListener('click', function() {
                document.getElementById('skip-confirm-modal').style.display = 'flex';
            });
            document.getElementById('cancelSkipBtn').addEventListener('click', function() {
                document.getElementById('skip-confirm-modal').style.display = 'none';
            });
            document.getElementById('confirmSkipBtn').addEventListener('click', function() {
                // Here you would typically redirect or proceed to the next step
                window.location.href = '/dashboard';
            });
            
            // Continue button functionality
            continueBtn.addEventListener('click', function() {
                if (selectedInterests.length > 0) {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                    fetch('/user-interests', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ interests: selectedInterests })
                    })
                    .then(response => {
                        if (response.redirected) {
                            window.location.href = response.url;
                        } else if (response.ok) {
                            window.location.href = '/dashboard';
                        } else {
                            return response.text().then(text => { throw new Error(text); });
                        }
                    })
                    .catch(error => {
                        showToast('Failed to submit interests: ' + error.message, 'error');
                    });
                }
            });
            
            // Update UI based on selection
            function updateSelectionUI() {
                const count = selectedInterests.length;
                countElement.textContent = count;
                
                // Enable/disable continue button
                continueBtn.disabled = count === 0;
            }
        });
    </script>
</body>
</html>