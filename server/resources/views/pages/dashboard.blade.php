@extends('layouts.app')
@section('title', 'Home | TRINWOPJ')
@section('content')
<div class="home_content">
    <div class="question-box">
        <input type="text" class="insight-btn question-input" placeholder="Type Your Question or Insight here" readonly/>
        <i class="bi bi-person-circle user-icon"></i>

    </div>
    <div class="posts-container" id="postsContainer">
        <!-- Posts will be rendered here by JavaScript -->
    </div>
</div>
@auth
<!-- Report Modal for Posts -->
<div id="reportModal" class="modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); align-items:center; justify-content:center;">
    <div class="modal-content" style="background:#fff; border-radius:12px; padding:2rem; min-width:320px; max-width:400px; box-shadow:0 8px 32px rgba(0,0,0,0.18); position:relative;">
        <button type="button" class="close-modal" style="position:absolute; top:12px; right:16px; background:none; border:none; font-size:1.5rem; color:#c92ae0; cursor:pointer;">&times;</button>
        <h3 style="color:#c92ae0; margin-bottom:1rem;">Report Post</h3>
        <form id="reportForm" method="POST">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="report_id" id="reportIdInput">
            <div style="margin-bottom:1rem;">
                <label for="reason" style="font-weight:600; color:#333;">Reason</label>
                <select name="reason" id="reasonSelect" required style="width:100%; padding:0.5rem; border-radius:6px; border:1px solid #ccc; margin-top:0.5rem;">
                    <option value="">Select a reason</option>
                    <option value="Spam">Spam</option>
                    <option value="Abusive">Abusive or harmful</option>
                    <option value="Off-topic">Off-topic</option>
                    <option value="Inappropriate">Inappropriate content</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div style="margin-bottom:1rem;">
                <label for="details" style="font-weight:600; color:#333;">Details (optional)</label>
                <textarea name="details" id="detailsInput" rows="3" style="width:100%; border-radius:6px; border:1px solid #ccc; padding:0.5rem;"></textarea>
            </div>
            <button type="submit" class="submit-report-btn" style="background:linear-gradient(135deg,#c92ae0,#a522b7); color:#fff; border:none; border-radius:6px; padding:0.6rem 1.5rem; font-weight:600; font-size:1rem; cursor:pointer; transition:background 0.2s;">Submit Report</button>
            <div id="reportError" style="color:#dc3545; margin-top:0.7rem; display:none;"></div>
        </form>
    </div>
</div>
@endauth
@push('scripts')
<script src="{{ asset('js/home.js') }}"></script>
<script>
    window.isAuthenticated = {{ Auth::check() ? 'true' : 'false' }};
</script>
@endpush
@endsection