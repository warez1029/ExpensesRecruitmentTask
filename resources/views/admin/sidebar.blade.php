<div class="col-md-3">
    <div class="panel panel-default panel-flush">
        <div class="panel-heading">
            Menu
        </div>

        <div class="panel-body">
            <ul class="nav" role="tablist">
                <li role="presentation">
                    <a href="{{ url('/home') }}">
                        Home
                    </a>
                    @role('admin')
                    <a href="{{ url('/users') }}">
                        Users
                    </a>
                    @endrole
                    <a href="{{ url('/expenses') }}">
                        Expenses
                    </a>
                    <a href="{{ url('/payments') }}">
                        Payments
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
