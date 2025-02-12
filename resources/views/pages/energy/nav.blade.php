<div class="nav-wrapper position-relative end-0 mb-4 w-14">
     <ul class="nav nav-pills nav-fill p-1 border border-shadow" role="tablist">
          <li class="nav-item">
               <a class="nav-link mb-0 px-0 py-1 {{str_contains(request()->url(), 'monitor') == true ? 'bg-gray-300' : '' }}"
                    href="{{ route('energy-monitor') }}" role="tab" aria-controls="code" aria-selected="false">
                    <i class="ni ni-laptop text-sm me-2"></i> Monitoring
               </a>
          </li>
          <li class="nav-item">
               <a class="nav-link mb-0 px-0 py-1 {{str_contains(request()->url(), 'control') == true ? 'bg-gray-300' : '' }}"
                    href="{{ route('energy-control') }}" role="tab" aria-controls="code" aria-selected="false">
                    <i class="ni ni-active-40 text-sm me-2"></i> Control
               </a>
          </li>
          <li class="nav-item">
               <a class="nav-link mb-0 px-0 py-1 {{str_contains(request()->url(), 'stats') == true ? 'bg-gray-300' : '' }}"
                    href="{{ route('energy-stats') }}" role="tab" aria-controls="code" aria-selected="false">
                    <i class="ni ni-chart-bar-32 text-sm me-2"></i> Statistics
               </a>
          </li>
     </ul>
</div>