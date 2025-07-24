@extends('layouts.app')

@section('title', 'Test Loading Functionality')

@section('content')
<div class="container mt-5 pt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2>Test Loading Functionality</h2>
                </div>
                <div class="card-body">
                    <p class="mb-4">This page demonstrates the global loading functionality for database interactions.</p>
                    
                    <div class="d-grid gap-3">
                        <button id="test-loading-bar" class="btn btn-primary">Test Loading Bar</button>
                        <button id="test-loading-modal" class="btn btn-secondary">Test Loading Modal</button>
                        <button id="test-fetch-get" class="btn btn-info">Test Fetch GET Request</button>
                        <button id="test-fetch-post" class="btn btn-warning">Test Fetch POST Request</button>
                    </div>
                    
                    <div class="mt-4">
                        <h4>Results:</h4>
                        <pre id="results" class="bg-light p-3 rounded">Results will appear here...</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resultsElement = document.getElementById('results');
        
        // Test loading bar
        document.getElementById('test-loading-bar').addEventListener('click', function() {
            resultsElement.textContent = 'Testing loading bar...';
            window.globalLoading.startLoadingBar();
            
            setTimeout(() => {
                window.globalLoading.completeLoadingBar();
                resultsElement.textContent = 'Loading bar test completed!';
            }, 2000);
        });
        
        // Test loading modal
        document.getElementById('test-loading-modal').addEventListener('click', function() {
            resultsElement.textContent = 'Testing loading modal...';
            window.globalLoading.showModal('Custom loading message...');
            
            setTimeout(() => {
                window.globalLoading.hideModal();
                resultsElement.textContent = 'Loading modal test completed!';
            }, 2000);
        });
        
        // Test fetch GET request
        document.getElementById('test-fetch-get').addEventListener('click', function() {
            resultsElement.textContent = 'Sending GET request...';
            
            fetch('/api/dashboard/posts')
                .then(response => response.json())
                .then(data => {
                    resultsElement.textContent = 'GET request completed!\n\nResponse: ' + JSON.stringify(data, null, 2);
                })
                .catch(error => {
                    resultsElement.textContent = 'Error: ' + error.message;
                });
        });
        
        // Test fetch POST request
        document.getElementById('test-fetch-post').addEventListener('click', function() {
            resultsElement.textContent = 'Sending POST request...';
            
            fetch('/api/test-post', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ test: 'data' })
            })
                .then(response => response.json())
                .catch(error => {
                    // Even if the endpoint doesn't exist, the loading indicators should work
                    resultsElement.textContent = 'POST request completed (endpoint may not exist)\n\nError: ' + error.message;
                });
        });
    });
</script>
@endpush