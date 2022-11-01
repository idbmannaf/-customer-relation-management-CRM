
@foreach ($attachments as $key=> $attachment)
<div class="attachment">
    <span class="serial"></span><a class="badge badge-success" href="{{ route('imagecache', [ 'template'=>'original','filename' => $attachment ]) }}"> {{$attachment}}</a> <a href="{{route('global.fileDelete',['file'=>$key])}}" class="badge badge-danger deleteAttachment"><i class="fas fa-times"></i></a>
</div>
@endforeach
