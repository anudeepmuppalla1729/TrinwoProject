@extends('layouts.app')
@section('title', 'Select Interests')
@section('content')
<div class="container">
    <div class="corner top-left"></div>
    <div class="corner top-right"></div>
    <div class="corner bottom-left"></div>
    <div class="corner bottom-right"></div>
    <h1>Interest’s</h1>
    <div class="grid">
        <div class="interest-box">
            <img src="{{ asset('assets/educat.jpeg') }}" alt="Education">
        </div>
        <div class="interest-box">
            <img src="{{ asset('assets/food.jpeg') }}" alt="Food">
            <span>Food</span>
        </div>
        <div class="interest-box">
            <img src="{{ asset('assets/history.jpeg') }}" alt="History">
        </div>
        <div class="interest-box">
            <img src="{{ asset('assets/technology.png') }}" alt="Technology">
            <span>Technology</span>
        </div>
        <div class="interest-box">
            <img src="{{ asset('assets/cook.jpeg') }}" alt="Cooking">
            <span>Cook</span>
        </div>
        <div class="interest-box">
            <img src="{{ asset('assets/dhoni.jpeg') }}" alt="Sports">
            <span>Sports</span>
        </div>
        <div class="interest-box">
            <img src="{{ asset('assets/art.jpeg') }}" alt="Art">
            <span>History</span>
        </div>
        <div class="interest-box">
            <img src="{{ asset('assets/salaar.jpeg') }}" alt="Movies">
            <span>Movies</span>
        </div>
    </div>
    <div class="buttons">
        <button class="skip-btn">Skip</button>
        <button class="continue-btn">Continue</button>
    </div>
</div>
@endsection 