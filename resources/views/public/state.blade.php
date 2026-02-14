<DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Browse Tech Companies in {{$state->name ?? 'All'}} | TechMedia</title>
        @include('partials.links')

        <link rel="stylesheet" href="{{ asset('css/states.css') }}">
        <style>
            .pagination {
                justify-content: center !important;
            }
        </style>
    </head>

    <body data-state-slug="{{$state->slug}}" data-state-name="{{$state->name}}" data-state-id="{{$state->id}}">

        @include('partials.navbar')

        <div class="page-header">
            <div class="container">
                <h1>Discover Tech Companies</h1>
                <p>Explore innovative companies across different states and industries</p>
            </div>
        </div>

        <div class="container">
            <!-- States Selection -->
            <div class="states-section">
                <div class="states-container">
                    <div class="section-title">
                        <i class="fas fa-map-marker-alt"></i>
                        <h2>Browse by State</h2>
                    </div>

                    <div class="states-scroll">
                        @foreach ($all_states as $s)
                        <a href="/states/{{$s->slug}}" class="state-chip {{ $state->slug == $s->slug ? 'active' : '' }}">
                            {{$s->name}}
                            <span class="count">{{$s->companies()->count()}}</span>
                        </a>
                        @endforeach
                        <a href="all-states.php" class="state-chip" style="border-style: dashed; background: transparent;">
                            View All <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="container">
                <div>
                    <h2>{{ $state->name }} Companies {{$state->companies()->count()}}</h2>
                </div>
            </div>


            <!-- Main Content -->
            <div class="row">
                <div class="content-wrapper col-md-3 bg-white p-4 filter-sidebar">
                    <!-- Sidebar Filter -->
                    <div class="category-group">
                        <div class="category-group-title">Technology</div>
                        <div class="category-list">
                            @foreach($categories as $category)
                            <div class="category-item">
                                <input type="checkbox"
                                    class="category-checkbox"
                                    id="cat_{{ $category->id }}"
                                    name="categories[]"
                                    value="{{ $category->id }}">
                                <label for="cat_{{ $category->id }}" class="category-label">
                                    {{ $category->name }}
                                </label>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <!-- Companies Grid -->
                <div class="companies-section col-md-9">
                    <div class="container">

                        <div id="companies-grid" class="row">

                        </div>
                        
                        <div id="companies-pagination">

                        </div>
                    </div>
                </div>
            </div>



        </div>

@include('partials.footer')
        <script>
            window.TECHMEDIA_URL_PATH = '';
        </script>
        <script src="{{asset('js/state-company-filter.js?34')}}"></script>
    </body>

    </html>