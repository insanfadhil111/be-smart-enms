<div class="nav-wrapper position-relative end-0 mb-4 w-14">
     <ul class="nav nav-pills nav-fill p-1 border border-shadow" role="tablist">
          <li class="nav-item">
               <a class="nav-link mb-0 px-0 py-1 {{str_contains(request()->url(), 'sense') == true ? 'bg-gray-300' : '' }}"
                    href="{{ route('envi-sense') }}" role="tab" aria-controls="code" aria-selected="false">
                    <i class="ni ni-laptop text-sm me-2"></i> Environmental Sensing
               </a>
          </li>
          <li class="nav-item">
               <a class="nav-link mb-0 px-0 py-1 {{str_contains(request()->url(), 'lights') == true ? 'bg-gray-300' : '' }}"
                    href="{{ route('envi-lights') }}" role="tab" aria-controls="code" aria-selected="false">
                    <i class="ni ni-active-40 text-sm me-2"></i> Lights Control
               </a>
          </li>
     </ul>
</div>