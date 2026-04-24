<!-- footer start-->
        <footer class="footer">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12 footer-copyright text-center">
                <div class="mb-3">
                  <img class="img-fluid mx-auto d-block" src="{{ $applicationSetting->displayFooterLogoUrl() }}" alt="{{ config('app.name') }}" style="max-height: 56px;">
                </div>
                <p class="mb-1 mb-md-2">{{ __('© :year SPOTMEE. All rights reserved.', ['year' => date('Y')]) }}</p>
                <p class="mb-0 text-muted small">{{ __('Designed & Developed By Liberty Web Studio') }}</p>
              </div>
            </div>
          </div>
        </footer>
