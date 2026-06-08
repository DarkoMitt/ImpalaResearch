@extends('layouts.app')

@section('content')
    <div
        x-data="{
            goal: '',
            method: '',
            country: '',
            audience: '',
            sampleSize: '400',
            timeline: '',
            notes: '',

            scrollTo(id) {
                document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
            },

            resetForm() {
                this.goal = '';
                this.method = '';
                this.country = '';
                this.audience = '';
                this.sampleSize = '400';
                this.timeline = '';
                this.notes = '';
                this.scrollTo('start-study');
            }
        }"
    >
        @include('partials.hero')
        <!-- @include('partials.study') -->
        @include('partials.study-builder')
        @include('partials.method-section')
        @include('partials.study-details')
        @include('partials.submit-request')
        @include('partials.footer')
    </div>
@endsection