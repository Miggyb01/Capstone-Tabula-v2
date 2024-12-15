@extends('firebase.layouts.judge-app')

@section('content')
<div class="container-fluid-dashboard-judge-user">
    <div class="row-dashboard-judge-user">
        <div class="col-12-dashboard-judge-user">
            <h3 class="welcome-title-dashboard-judge-user">
                Welcome, {{ session('user.name') }}
            </h3>
        </div>
    </div>

    @if(isset($error))
        <div class="alert-warning-dashboard-judge-user" role="alert">
            {{ $error }}
        </div>
    @else
        <div class="row-content-dashboard-judge-user">
            <div class="col-main-dashboard-judge-user">
                <div class="card-main-dashboard-judge-user">
                    <div class="banner-container-dashboard-judge-user">
                        @if(isset($eventData['banner_url']))
                            <img src="{{ $eventData['banner_url'] }}" 
                                class="event-banner-dashboard-judge-user" 
                                alt="Event Banner"
                                onerror="this.onerror=null; this.src='/api/placeholder/800/400'; this.classList.add('fallback-image-dashboard-judge-user');">
                        @else
                            <div class="no-banner-dashboard-judge-user">
                                <i class="ri-image-line icon-dashboard-judge-user"></i>
                                <span class="no-banner-text-dashboard-judge-user">No Banner Available</span>
                            </div>
                        @endif
                    </div>
                    <div class="card-body-dashboard-judge-user">
                        <h2 class="event-title-dashboard-judge-user">
                            {{ $eventData['ename'] ?? 'Event Name Not Available' }}
                        </h2>
                        <p class="event-description-dashboard-judge-user">
                            {{ $eventData['edescription'] ?? 'No description available' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="col-details-dashboard-judge-user">
                <div class="card-details-dashboard-judge-user">
                    <div class="card-body-details-dashboard-judge-user">
                        <h4 class="details-title-dashboard-judge-user">Event Details</h4>
                        
                        <div class="detail-item-dashboard-judge-user">
                            <i class="ri-calendar-event-line icon-dashboard-judge-user"></i>
                            <div class="detail-content-dashboard-judge-user">
                                <span class="detail-label-dashboard-judge-user">Date</span>
                                <span class="detail-value-dashboard-judge-user">
                                    {{ isset($eventData['edate']) ? date('F d, Y', strtotime($eventData['edate'])) : 'Not specified' }}
                                </span>
                            </div>
                        </div>

                        <div class="detail-item-dashboard-judge-user">
                            <i class="ri-time-line icon-dashboard-judge-user"></i>
                            <div class="detail-content-dashboard-judge-user">
                                <span class="detail-label-dashboard-judge-user">Time</span>
                                <span class="detail-value-dashboard-judge-user">
                                    {{ isset($eventData['estart']) ? date('h:i A', strtotime($eventData['estart'])) : 'Not specified' }}
                                    -
                                    {{ isset($eventData['eend']) ? date('h:i A', strtotime($eventData['eend'])) : 'Not specified' }}
                                </span>
                            </div>
                        </div>

                        <div class="detail-item-dashboard-judge-user">
                            <i class="ri-map-pin-line icon-dashboard-judge-user"></i>
                            <div class="detail-content-dashboard-judge-user">
                                <span class="detail-label-dashboard-judge-user">Venue</span>
                                <span class="detail-value-dashboard-judge-user">
                                    {{ $eventData['evenue'] ?? 'Not specified' }}
                                </span>
                            </div>
                        </div>

                        <div class="detail-item-dashboard-judge-user">
                            <i class="ri-user-star-line icon-dashboard-judge-user"></i>
                            <div class="detail-content-dashboard-judge-user">
                                <span class="detail-label-dashboard-judge-user">Organizer</span>
                                <span class="detail-value-dashboard-judge-user">
                                    {{ $eventData['eorganizer'] ?? 'Not specified' }}
                                </span>
                            </div>
                        </div>

                        <div class="detail-item-dashboard-judge-user">
                            <i class="ri-folder-info-line icon-dashboard-judge-user"></i>
                            <div class="detail-content-dashboard-judge-user">
                                <span class="detail-label-dashboard-judge-user">Event Type</span>
                                <span class="detail-value-dashboard-judge-user">
                                    {{ $eventData['etype'] ?? 'Not specified' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
.event-banner-dashboard-judge-user {
    width: 100%;
    height: 100%;
    object-fit: cover;
    background-color: #f8f9fa;
    transition: opacity 0.3s ease;
}

.event-banner-dashboard-judge-user.loading-dashboard-judge-user {
    opacity: 0;
}

.fallback-image-dashboard-judge-user {
    object-fit: contain;
    padding: 20px;
    background-color: #f8f9fa;
}

.banner-container-dashboard-judge-user {
    position: relative;
    width: 100%;
    height: 300px;
    overflow: hidden;
    background-color: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
}

.container-fluid-dashboard-judge-user {
    padding: 2rem;
}

.row-dashboard-judge-user,
.row-content-dashboard-judge-user {
    display: flex;
    flex-wrap: wrap;
    margin: 0 -1rem;
}

.col-12-dashboard-judge-user {
    flex: 0 0 100%;
    max-width: 100%;
    padding: 0 1rem;
}

.col-main-dashboard-judge-user {
    flex: 0 0 100%;
    max-width: 100%;
    padding: 0 1rem;
    margin-bottom: 2rem;
}

.col-details-dashboard-judge-user {
    flex: 0 0 100%;
    max-width: 100%;
    padding: 0 1rem;
}

@media (min-width: 992px) {
    .col-main-dashboard-judge-user {
        flex: 0 0 66.666667%;
        max-width: 66.666667%;
        margin-bottom: 0;
    }
    
    .col-details-dashboard-judge-user {
        flex: 0 0 33.333333%;
        max-width: 33.333333%;
    }
}

.welcome-title-dashboard-judge-user {
    font-size: 1.75rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem;
}

.card-main-dashboard-judge-user,
.card-details-dashboard-judge-user {
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.banner-container-dashboard-judge-user {
    position: relative;
    width: 100%;
    height: 300px;
    overflow: hidden;
    background-color: #f8f9fa;
}

.event-banner-dashboard-judge-user {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-banner-dashboard-judge-user {
    width: 100%;
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #6c757d;
}

.icon-dashboard-judge-user {
    font-size: 1.25rem;
    color: #3155FE;
    margin-right: 1rem;
}

.card-body-dashboard-judge-user,
.card-body-details-dashboard-judge-user {
    padding: 1.5rem;
}

.event-title-dashboard-judge-user {
    font-size: 2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1rem;
}

.event-description-dashboard-judge-user {
    font-size: 1rem;
    color: #6c757d;
    line-height: 1.6;
}

.details-title-dashboard-judge-user {
    font-size: 1.25rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 1.5rem;
}

.detail-item-dashboard-judge-user {
    display: flex;
    align-items: start;
    margin-bottom: 1.25rem;
    padding-bottom: 1.25rem;
    border-bottom: 1px solid #dee2e6;
}

.detail-item-dashboard-judge-user:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.detail-content-dashboard-judge-user {
    flex: 1;
}

.detail-label-dashboard-judge-user {
    display: block;
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
}

.detail-value-dashboard-judge-user {
    display: block;
    font-size: 1rem;
    color: #333;
    font-weight: 500;
}

.alert-warning-dashboard-judge-user {
    background-color: #fff3cd;
    color: #856404;
    padding: 1rem 1.25rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.no-banner-text-dashboard-judge-user {
    margin-top: 0.5rem;
    font-size: 0.875rem;
}

.icon-dashboard-judge-user.ri-image-line {
    font-size: 3rem;
    margin-bottom: 0.5rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bannerImage = document.querySelector('.event-banner-dashboard-judge-user');
    if (bannerImage) {
        bannerImage.classList.add('loading-dashboard-judge-user');
        bannerImage.onload = function() {
            bannerImage.classList.remove('loading-dashboard-judge-user');
        };
    }
});
</script>
@endsection