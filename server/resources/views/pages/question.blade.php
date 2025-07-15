@extends('layouts.app')
@section('title', 'Question Detail')
@section('content')
<div class="question-page">
    <div class="question-nav"></div>
    <div id="question-container" class="question-heading">
        <h2>How will Blockchain Technology impact the future of industries?</h2>
        <p>Blockchain technology is more than just the foundation of cryptocurrencies. It represents a decentralized, tamper-proof digital ledger capable of transforming how industries operate. From financial institutions to healthcare providers and supply chains, the impact of blockchain is expected to be revolutionary.</p>
    </div>
    <h4>Answers</h4>
    <div id="answers-container"></div>
    <form id="answer-form">
        <input type="text" id="answer-input" placeholder="Type your answer..." required />
        <button type="submit">Submit</button>
    </form>
    <button id="back-home">Back to Home</button>
    <button id="edit-question">Edit Question</button>
</div>
@push('scripts')
<script src="{{ asset('js/question.js') }}"></script>
@endpush
@endsection 