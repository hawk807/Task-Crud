@extends('tasks.layout')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Edit Task</h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('tasks.index') }}"> Back</a>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('tasks.update',$task->id) }}" method="POST">
        @csrf
        @method('PUT')

         <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Task Details:</strong>
                    <textarea class="form-control" style="height:150px" name="task">{{ $task->task }}</textarea>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Date:</strong>
                    <input type="datetime-local" name="date" onchange="FetchTimeDifference()" id="date" value="{{$task->date}}" class="form-control">
                    <input type="hidden"  value="{{ $task->timezone_difference }}" id="timezone" name="timezone_difference" class="form-control">
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>

    </form>
    <script>
        function FetchTimeDifference()
        {
            var my_selected_time = document.getElementById('date').value;
            var timezone_offset_minutes = new Date(my_selected_time).getTimezoneOffset();
            timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
            document.getElementById('timezone').value = timezone_offset_minutes;
        }

    </script>
@endsection
