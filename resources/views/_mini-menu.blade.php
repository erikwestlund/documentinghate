<div class="col-sm-10 col-sm-offset-1  top-margin-lg bottom-margin-lg mini-menu">

    <div class="col-sm-4 document-incident text-center">
        <a href="{{ url('/add') }}"><i class="fa fa-pencil"></i> Document an Incident</a>
    </div>        
    <div class="col-sm-4 text-center search-box">
        <form method="GET" action="{{ url('/')}}">
            <input type="text" name="search" placeholder="Search" class="form-control search"><i class="search-icon fa fa-search"></i>
        </form>
    </div>
    <div class="col-sm-4 text-center remembering-love">
        <a href="http://rememberingloveinthe.us/"><i class="fa fa-heart"></i> Remembering Love</a>
    </div>
</div>