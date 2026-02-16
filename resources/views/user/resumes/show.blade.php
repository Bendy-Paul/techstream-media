@extends('layouts.user')

@section('content')
<style>
    /* Print-specific styles for A4 layout and ATS friendliness */
    @media print {
        @page {
            margin: 1.27cm; /* 0.5 inch margins are common for resumes to fit more content */
            size: A4;
        }
        body {
            background: white !important;
            font-family: Arial, Helvetica, sans-serif !important;
            font-size: 11pt; /* Ideal size for body text */
            color: #000 !important;
        }
        .no-print { display: none !important; }
        .a4-page {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
        }
        /* ATS optimization: ensure text is in a single flow */
        .resume-section-header {
            border-bottom: 1.5pt solid #000 !important;
            margin-bottom: 10pt !important;
            margin-top: 15pt !important;
            font-size: 13pt !important;
            text-transform: uppercase;
            font-weight: bold;
        }
        .date-text {
            float: none !important; /* Don't float in print to ensure logical reading order */
            display: block;
            margin-bottom: 2pt;
            font-size: 10pt;
        }
    }

    /* Screen styles */
    @media screen {
        .a4-container {
            background-color: #f8f9fa;
            padding: 3rem 0;
            min-height: 100vh;
        }
        .a4-page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            font-family: Arial, Helvetica, sans-serif;
        }
    }

    /* Common Styles */
    .resume-content { line-height: 1.5; color: #000; }
    .resume-header h1 { font-size: 24pt; margin-bottom: 5pt; }
    .resume-header p { font-size: 11pt; margin-bottom: 2pt; }
    .resume-section-header { 
        font-size: 14pt; 
        font-weight: bold; 
        text-transform: uppercase; 
        border-bottom: 2px solid #333; 
        margin-top: 20pt;
        margin-bottom: 10pt;
    }
    .item-title { font-size: 12pt; font-weight: bold; }
    .item-subtitle { font-size: 11pt; font-weight: bold; font-style: italic; }
    .date-text { font-size: 11pt; color: #333; }
    .skill-list { list-style: none; padding: 0; }
    .skill-item { display: inline; }
    .skill-item:not(:last-child):after { content: " • "; margin: 0 5px; }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 no-print container">
    <h2 class="h4 mb-0">ATS-Optimized Preview</h2>
    <div>
        <button onclick="window.print()" class="btn btn-dark btn-sm me-2">
            <i class="fas fa-file-pdf me-1"></i> Download PDF
        </button>
        <a href="{{ route('user.resumes.edit', $resume->id) }}" class="btn btn-outline-primary btn-sm me-2">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <a href="{{ route('user.resumes.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-arrow-left me-1"></i> Back
        </a>
    </div>
</div>

<div class="a4-container">
    <div class="a4-page resume-content">
        <header class="resume-header text-center mb-4">
            <h1 class="fw-bold">{{ auth()->user()->name }}</h1>
            <p>{{ auth()->user()->email }} | {{ auth()->user()->phone ?? 'Phone Number' }}</p>
        </header>

        @if($resume->summary)
        <section class="mb-4">
            <div class="resume-section-header">Professional Summary</div>
            <p style="font-size: 11pt;">{{ $resume->summary }}</p>
        </section>
        @endif

        @if($resume->experiences->count() > 0)
        <section class="mb-4">
            <div class="resume-section-header">Professional Experience</div>
            @foreach($resume->experiences as $exp)
                <div class="mb-3">
                    <div class="d-flex justify-content-between">
                        <span class="item-title">{{ $exp->job_title }}</span>
                        <span class="date-text">
                            {{ $exp->start_date ? $exp->start_date->format('M Y') : '' }} – 
                            {{ $exp->is_current ? 'Present' : ($exp->end_date ? $exp->end_date->format('M Y') : '') }}
                        </span>
                    </div>
                    <div class="item-subtitle">{{ $exp->company_name }}</div>
                    <p class="mt-1" style="font-size: 11pt; white-space: pre-line;">{{ $exp->description }}</p>
                </div>
            @endforeach
        </section>
        @endif

        @if($resume->education->count() > 0)
        <section class="mb-4">
            <div class="resume-section-header">Education</div>
            @foreach($resume->education as $edu)
                <div class="mb-2">
                    <div class="d-flex justify-content-between">
                        <span class="item-title">{{ $edu->institution }}</span>
                        <span class="date-text">
                            {{ $edu->start_date ? $edu->start_date->format('Y') : '' }} – 
                            {{ $edu->end_date ? $edu->end_date->format('Y') : '' }}
                        </span>
                    </div>
                    <div>{{ $edu->degree }}{{ $edu->field_of_study ? ', ' . $edu->field_of_study : '' }}</div>
                </div>
            @endforeach
        </section>
        @endif

        @if(isset($skillStacks) && $skillStacks->count() > 0)
        <section class="mb-4">
            <div class="resume-section-header">Skills</div>
            <p class="skill-list" style="font-size: 11pt;">
                @foreach($skillStacks as $stack)
                    <span class="skill-item">{{ $stack->name }}</span>
                @endforeach
            </p>
        </section>
        @endif
    </div>
</div>
@endsection