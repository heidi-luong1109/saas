<div class="row">
    <div class="col-12 col-md-6 col-lg-6">
        <div class="form-group">
            <label>{{ __('Title')}} :</label>
            <p class="m-0 p-0">{{$task->title}}</p>
        </div>
    </div>
    <div class="col-12 col-md-6 col-lg-6">
        <div class="form-group">
            <label>{{ __('Priority')}} :</label>
            <p class="m-0 p-0">
            @if($task->priority =='low')
                <div class="label label-soft-success font-style"> {{ $task->priority }}</div>
            @elseif($task->priority =='medium')
                <div class="label label-soft-warning font-style"> {{ $task->priority }}</div>
            @elseif($task->priority =='high')
                <div class="label label-soft-danger font-style"> {{ $task->priority }}</div>
                @endif
                </p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12">
        <div class="form-group">
            <label>{{ __('Description')}} :</label>
            <p class="m-0 p-0">{{$task->description}}</p>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12 col-md-4 col-lg-4">
        <div class="form-group">
            <label>{{ __('Start Date')}} :</label>
            <p class="m-0 p-0">{{$task->start_date}}</p>
        </div>
    </div>
    <div class="col-12 col-md-4 col-lg-4">
        <div class="form-group">
            <label>{{ __('Due Date')}} :</label>
            <p class="m-0 p-0">{{$task->due_date}}</p>
        </div>
    </div>
    <div class="col-12 col-md-4 col-lg-4">
        <div class="form-group">
            <label>{{ __('Milestone')}} :</label>
{{--            <p class="m-0 p-0">{{!empty($task->milestone)?$task->milestone->title:''}}</p>--}}
        </div>
    </div>
</div>
<div class="task-inner-tab">
    <ul class="nav nav-pills" id="myTab" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"> {{__('Checklist')}} </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"> {{__('Comments')}} </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false"> {{__('Files')}} </a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active mt-3" id="home" role="tabpanel" aria-labelledby="home-tab">
            <div class="progress-wrap">
                <div class="tab-pane fad active" id="tab_1_3">
                    <div class="row">
                        <div class="col-md-11">
                            <div class="row mb-10">
                                <div class="col-md-6">
                                    <b>{{__('Progress')}}</b>
                                </div>
                                <div class="col-md-6 text-right">
                                    <b>
                                        <span class="progressbar-label custom-label" style="margin-top: -9px !important;margin-left: .7rem">
                                            0%
                                        </span>
                                    </b>
                                </div>
                            </div>
                            <div class="text-left">
                                <div class="custom-widget__item flex-fill">
                                    <div class="custom-widget__progress d-flex  align-items-center">
                                        <div class="progress" style="height: 5px;width: 100%;">
                                            <div class="progress-bar" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" id="taskProgress"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-1">
                            <div class="text-right mb-1">
                                <a href="#" class="btn btn-sm btn-primary" data-toggle="collapse" data-target="#form-checklist"><i class="fa fa-plus"></i></a>
                            </div>
                        </div>

                        <form method="POST" id="form-checklist" class="collapse col-md-12" data-action="{{ route('project.task.checklist.store',[$task->id]) }}">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <div class="form-group">
                                <label>{{__('Name')}}</label>
                                <input type="text" name="name" class="form-control" required placeholder="{{__('Checklist Name')}}">
                            </div>
                            <div class="text-right">
                                <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                                    <button type="button" class="btn btn-primary form-checklist">{{ __('Create')}}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="row">
                        <ul class="col-md-12" id="check-list">
                            @foreach($task->taskCheckList as $checkList)
                                <li class="media">
                                    <div class="media-body">
                                        <h5 class="mt-0 mb-1 font-weight-bold"></h5>
                                        <div class=" custom-control custom-checkbox checklist-checkbox">
                                            <input type="checkbox" id="checklist-{{$checkList->id}}" class="custom-control-input taskCheck" {{($checkList->status==1)?'checked':''}} value="{{$checkList->id}}" data-url="{{route('project.task.checklist.update',[$checkList->task_id,$checkList->id])}}">
                                            <label for="checklist-{{$checkList->id}}" class="custom-control-label"></label>
                                            {{$checkList->name}}
                                        </div>
                                        <div class="comment-trash" style="float: right">
                                            <a href="#" class="btn btn-outline btn-sm red text-muted delete-checklist" data-url="{{route('project.task.checklist.destroy',[$checkList->task_id,$checkList->id])}}">
                                                <i class="far fa-trash-alt"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-pane fade mt-3" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <div class="form-group m-0">
                <form method="post" id="form-comment" data-action="{{route('project.task.comment.store',[$task->project_id,$task->id])}}">
                    <textarea class="form-control" name="comment" placeholder="{{ __('Write message')}}" id="example-textarea" rows="3" required></textarea>
                    <div class="text-right mt-10">
                        <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                            <button type="button" class="btn btn-primary">{{ __('Submit')}}</button>
                        </div>
                    </div>
                </form>
                <div class="comment-holder" id="comments">
                    @foreach($task->comments as $comment)
                        <div class="media">
                            <div class="media-body">
                                <div class="d-flex justify-content-between align-items-end">
                                    <div>
                                        <h5 class="mt-0">{{(!empty($comment->user)?$comment->user->name:'')}}</h5>
                                        <p class="mb-0">{{$comment->comment}}</p>
                                    </div>
                                    <a href="#" class="btn btn-outline btn-sm red text-muted  delete-comment" data-url="{{route('project.task.comment.destroy',[$comment->id])}}">
                                        <i class="far fa-trash-alt"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="tab-pane fade mt-3" id="contact" role="tabpanel" aria-labelledby="contact-tab">
            <div class="form-group m-0">
                <form method="post" id="form-file" enctype="multipart/form-data" data-url="{{ route('project.task.comment.file.store',$task->id) }}">
                    @csrf
                    <input type="file" class="form-control mb-2" name="file" id="file">
                    <span class="invalid-feedback" id="file-error" role="alert">
                        <strong></strong>
                    </span>
                    <div class="text-right mt-10">
                        <div class="btn-group mb-2 ml-2 d-none d-sm-inline-block">
                            <button type="submit" class="btn btn-primary">{{ __('Upload')}}</button>
                        </div>
                    </div>
                </form>
                <div class="row">
                    <ul class="col-md-12" id="comments-file">
                        @foreach($task->taskFiles as $file)
                            <li class="media">
                                <div class="media-body">
                                    <h6 class="mt-0 mb-1 font-weight-bold"> {{$file->name}}</h6>
                                    {{$file->file_size}}
                                    <div class="comment-trash" style="float: right">
                                        <a download href="{{asset(Storage::url('uploads/tasks/'.$file->file))}}" class="btn btn-outline btn-sm blue-madison">
                                            <i class="fa fa-download"></i>
                                        </a>
                                        <a href="#" class="btn btn-outline btn-sm red text-muted delete-comment-file" data-url="{{route('project.task.comment.file.destroy',[$file->id])}}">
                                            <i class="far fa-trash-alt"></i>
                                        </a>

                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
