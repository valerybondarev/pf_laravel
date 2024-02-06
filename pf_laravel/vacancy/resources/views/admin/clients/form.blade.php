<div class="row justify-content-center">
    <div class="col-lg-6 card-wrapper">
        <div class="card">
            <div class="card-header">
                <div class="nav-wrapper mx-5">
                    <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                        {{--<li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-news-body" data-toggle="tab" href="#news-body" role="tab"
                               aria-controls="fade"
                               aria-selected="false">{{ __('admin.columns.messages') }}</a>
                        </li>--}}
                        <li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0 active" id="tab-common" data-toggle="tab" href="#common" role="tab"
                               aria-controls="common"
                               aria-selected="true">{{ __('admin.sections.common') }}</a>
                        </li>
                        @env('local')
                            <li class="nav-item">
                                <a class="nav-link mb-sm-3 mb-md-0" id="tab-common" data-toggle="tab" href="#test" role="tab"
                                   aria-controls="common"
                                   aria-selected="true">{{ __('admin.sections.test') }}</a>
                            </li>
                        @endenv
                        {{--<li class="nav-item">
                            <a class="nav-link mb-sm-3 mb-md-0" id="tab-common" data-toggle="tab" href="#tests" role="tab"
                               aria-controls="common"
                               aria-selected="true">{{ __('admin.sections.tests') }}</a>
                        </li>--}}
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    <div class="tab-pane fade show active" id="common" role="tabpanel" aria-labelledby="tab-common">
                        @include('admin.clients.form.common')
                    </div>
                    {{--<div class="tab-pane fade" id="news-body" role="tabpanel" aria-labelledby="tab-news-body">
                        @include('admin.clients.form.messages')
                    </div>--}}
                    <div class="tab-pane fade" id="test" role="tabpanel" aria-labelledby="tab-test">
                        @include('admin.clients.form.test')
                    </div>
                    {{--<div class="tab-pane fade" id="tests" role="tabpanel" aria-labelledby="tab-tests">
                        @include('admin.clients.form.tests')
                    </div>--}}

                </div>
            </div>
        </div>
    </div>
</div>
