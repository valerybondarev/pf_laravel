<div class="header">
    <div class="container-fluid">
        <div class="header-body">
            <div class="row align-items-center py-4">
                <div class="col-lg-6 col-7">
                    @isset($title)
                        <h6 class="h2 text-white d-inline-block mb-0">{{ $title }}</h6>
                    @endisset
                    @if($breadcrumbs ?? true)
                        @include('admin.layouts.components.breadcrumbs')
                    @endif
                </div>
                @isset($description)
                    <div class="col-md-12 col-lg-7">
                        <p class="text-white mt-0 mb-5">{{ $description }}</p>
                    </div>
                @endisset

            </div>
            @include('admin.layouts.components.message')
        </div>
    </div>
</div>
