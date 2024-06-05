@extends('layouts.admin')
@section('title', $project->title)

@section('content')
<section>
    @if(session()->has('message'))
    <div class="alert alert-success">{{session()->get('message')}}</div>
  @endif
    <h1>{{$project->title}}</h1>

    <p>{{$project->content}}</p>
    <img src="{{asset('storage/'.$project->image)}}" alt="{{$project->title}}">
</section>
@endsection