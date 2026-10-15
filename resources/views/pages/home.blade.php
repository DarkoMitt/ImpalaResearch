
@extends('layouts.app')

@section('content')
<div
    x-data="{
        goal: @js(old('goal', '')),
        methods: @js(old('methods', [])),
        country: @js(old('country', '')),
        audience: @js(old('audience', '')),
        sampleSize: @js(old('sample_size', '400')),
        timeline: @js(old('timeline', '')),
        notes: @js(old('notes', '')),

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
        },

        validateStudy() {
            if (!this.goal) {
                alert('Please select your research goal.');
                this.scrollTo('start-study');
                return false;
            }

            if (!this.methods.length) {
                alert('Please select at least one research method.');
                this.scrollTo('method');
                return false;
            }

            if (!this.country) {
                alert('Please select your target country.');
                this.scrollTo('study-details');
                return false;
            }

            if (!this.audience.trim()) {
                alert('Please enter your target audience.');
                this.scrollTo('study-details');
                return false;
            }

            const size = Number(this.sampleSize);

            if (!Number.isInteger(size) || size < 1 || size > 1000) {
                alert('Sample size must be between 1 and 1000.');
                this.scrollTo('study-details');
                return false;
            }

            return true;
        }
    }"

    x-init="
        $nextTick(() => {
            if (@js($errors->any())) {
                scrollTo('submit-request');
            }
        });
    "
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
