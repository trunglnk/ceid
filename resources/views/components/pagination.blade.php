<div class="row">
    <div class="col-sm-5">
      <div class="dataTables_info" role="status" aria-live="polite">{{__('component.pagination.title')}} {{(($data->currentPage() -1) * $data->perPage()) + 1 }} {{__('component.pagination.to')}} {{$data->count()+$data->perPage()*($data->currentPage()-1)}} {{__('component.pagination.of')}} {{$data->total()}} {{__('component.pagination.entries')}}</div>
    </div>
    <div class="col-sm-7">
      <div class="dataTables_paginate paging_simple_numbers">
        <ul class="pagination">          
        {{$data->appends(request()->query())->links()}}     
        </ul>
      </div>
    </div>
    <input type="hidden" value="{{$data->currentPage()}}" id="current_page">
  </div>