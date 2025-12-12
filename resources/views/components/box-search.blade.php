<div class="box-search">
    <form class="form-horizontal" id="search-form" role="form" action={{ empty($id) ? route($routerName) : route($routerName, $id)}} method="GET">
        <div class="input-group pull-right" id="adv-search">
            <input type="text" class="form-control" value="{{$search}}" name="search" placeholder="{{__('component.box-search.placeholder')}}" />
            <span class="input-group-btn">
                <div class="btn-group" style="height: 40px;">
                    <button type="button" class="btn btn-flat bg-olive button-search"><span class="caret"></span></button>
                    <button type="submit" class="btn bg-olive pull-right flat"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                </div>
            </span>
        </div>
        <div class="dropdown dropdown-lg" id="dropdown">
            <div class="dropdown-menu dropdown-menu-right">
                <div class="box-body">
                    {{$slot}}
                </div>
                <div class="box-footer">
                    <a href="{{ empty($id) ? route($routerName) : route($routerName, $id)}}" class="btn btn-flat btn-default"><i class="fa fa-history"></i> {{__('component.box-search.reset')}}</a>
                    <button type="submit" class="btn btn-flat bg-olive pull-right"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> {{__('component.box-search.search')}}</button>
                </div>
            </div>
        </div>
    </form>
</div>

<script type="application/javascript">
    $(document).ready(function() {
        $form = $('search-form')
        $('#search-form input').keyup(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $form.submit();
            }
        });
        $('#search-form #adv-search').keyup(function(event) {
            if (event.which === 13) {
                event.preventDefault();
                $form.submit();
            }
        });
    });
</script>
