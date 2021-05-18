<!DOCTYPE html>
<html lang="en">
<meta name="csrf-token" content="{{ csrf_token() }}">
@include('partials.admin.head')
<body>
@include('partials.admin.menu')
<div class="main-content" id="panel">
@include('partials.admin.header')
    @include('partials.admin.content')
</div>
<div id="commonModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modelCommanModelLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content ">
            <div class="modal-header">
                <h4 class="modal-title" id="modelCommanModelLabel"></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body"></div>
        </div>
    </div>
</div>
@include('partials.admin.footer')
</body>

</html>
