<div class="nav-wrapper position-relative end-0 mb-4">
     <ul class="nav nav-pills nav-fill p-1 border border-shadow" role="tablist">
          <li class="nav-item">
               <a class="nav-link mb-0 px-0 py-1 {{str_contains(request()->url(), 'camera') == true ? 'bg-gray-300' : '' }}"
                    href="{{ url('security-camera') }}" role="tab" aria-controls="code" aria-selected="false">
                    <i class="ni ni-laptop text-sm me-2"></i> Camera
               </a>
          </li>
          <li class="nav-item">
               <a class="nav-link mb-0 px-0 py-1 {{str_contains(request()->url(), 'doorlock') == true ? 'bg-gray-300' : '' }}"
                    href="{{ url('security-doorlock') }}" role="tab" aria-controls="code" aria-selected="false">
                    <i class="ni ni-active-40 text-sm me-2"></i> Doorlock
               </a>
          </li>
     </ul>
</div>