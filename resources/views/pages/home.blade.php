@extends('layouts.app')

@section('content')
<div
    x-data="{
        goal: '',
        methods: [],
        country: '',
        audience: '',
        sampleSize: '400',
        timeline: '',
        notes: '',

        scrollTo(id) {
            document.getElementById(id)?.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        },

        resetForm() {
            this.goal = '';
            this.methods = [];
            this.country = '';
            this.audience = '';
            this.sampleSize = '400';
            this.timeline = '';
            this.notes = '';

            this.scrollTo('start-study');
        }
    }"
>
        @include('partials.navbar')

        @include('partials.hero')

        @include('partials.study-progress')

        @include('partials.study-builder')
        @include('partials.method-section')
        @include('partials.study-details')
        @include('partials.submit-request')

        @include('partials.footer')
        </div>
        @endsection