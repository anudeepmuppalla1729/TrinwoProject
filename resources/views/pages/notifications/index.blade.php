@extends('layouts.profile')

@section('title', 'Notifications | Inqube')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
@endpush

@section('main_content')

<div class="page-content active">
    <div class="notifications-container">
        <div class="notifications-header">
            <h2>
                <i class="fas fa-bell"></i>
                <span>Notifications</span>
            </h2>
            <div class="notifications-actions">
                <button class="btn btn-outline-primary btn-sm" id="markAllRead">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
            </div>
        </div>

        <div class="notifications-filters">
            <button class="filter-btn active" data-filter="all">All</button>
            <button class="filter-btn" data-filter="unread">Unread</button>
            <button class="filter-btn" data-filter="welcome">Welcome</button>
            <button class="filter-btn" data-filter="follower">Followers</button>
            <button class="filter-btn" data-filter="milestone">Milestones</button>
            <button class="filter-btn" data-filter="upvote">Upvotes</button>
            <button class="filter-btn" data-filter="comment">Comments</button>
            <button class="filter-btn" data-filter="reply">Replies</button>
            <button class="filter-btn" data-filter="report">Reports</button>
        </div>

        <div class="notifications-list" id="notificationList">
            <!-- Loading state with better text -->
            <div class="loading-spinner">
                <i class="fas fa-spinner fa-spin"></i>
                <p>Loading your notifications...</p>
            </div>
        </div>

        <div class="notifications-empty" id="notificationEmpty" style="display: none;">
            <div class="empty-state">
                <i class="fas fa-bell-slash"></i>
                <h3>No notifications yet</h3>
                <p>When you receive notifications, they'll appear here.</p>
            </div>
        </div>

        {{-- Remove pagination controls --}}
        {{-- <div class="notifications-pagination" id="notificationsPagination">
             <!-- Pagination will be added here -->
        </div> --}}
    </div>

    <!-- Notification Item Template -->
    <template id="notificationTemplate">
        <div class="notification-item" data-id="" data-type="">
            <div class="notification-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="notification-content">
                <div class="notification-header">
                    <h4 class="notification-title"></h4>
                    <span class="notification-time"></span>
                </div>
                <p class="notification-message"></p>
                <div class="notification-sender">
                    <!-- JS will need to render either an <img> or initials span here -->
                    <span class="sender-avatar default-avatar notification-initials"></span>
                    <span class="sender-name"></span>
                </div>
            </div>
            <div class="notification-actions">
                <button class="btn btn-sm btn-outline-primary mark-read-btn" title="Mark as read">
                    <i class="fas fa-check"></i>
                </button>
            </div>
            <div class="notification-link" style="display: none;">
                <a href="" class="btn btn-sm btn-primary">View</a>
            </div>
        </div>
    </template>

</div>
@endsection

@push('scripts')
<script src="{{ asset('js/notifications.js') }}"></script>
@endpush 